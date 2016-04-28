<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\OrderSearch;
use app\models\OrderToProduct;
use app\models\Quotation;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'ruleConfig' => [
                    'class' => AccessRule::className(),
                ],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['Admin'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['Admin', 'Supplier'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new OrderSearch();
        
        $searchModel->status = Order::STATUS_ACTIVE;
        
        if ($id) {
            $searchModel->quotation = $id;
        }
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $productQuery = $model->getOrderToProducts();

        $dataProvider = new ActiveDataProvider([
            'query' => $productQuery,
            'sort' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $orderPostData = Yii::$app->request->post('Order');

        if (isset($orderPostData['id']) && $orderPostData['id']>0) {
            $model = $this->findModel($orderPostData['id']);
        } else {
            $model = new Order();
        }
        
        if ($id) {
            if (
                Quotation::findOne(['id' => $id])->status === Quotation::STATUS_INACTIVE ||
                Quotation::findOne(['id' => $id])->status === Quotation::STATUS_DELETED) {
                throw new ForbiddenHttpException('Нельзя создавать заказы на некативные или удаленные предложения');
            }
        } else {
            $firstQuotation = Quotation::getFirst();

            if (!$firstQuotation) {
                $message = "Извините, нет подходящих предложений";
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['index']);
            }
            $id = $firstQuotation->id;
        }
        $model->quotation = $id;

        $model->load(Yii::$app->request->post());

        $postQuantityArray = Yii::$app->request->post('quantity');
        $postPriceArray = Yii::$app->request->post('price');

        if (is_array($postQuantityArray) && is_array($postPriceArray)) {
            $model->save();
            foreach ($postQuantityArray as $productId => $quantity) {
                $orderToProduct = OrderToProduct::find()->where(['order' => $model->id, 'product' => $productId])->one();
                if (!$orderToProduct) {
                    $orderToProduct = new OrderToProduct();
                    $orderToProduct->order = $model->id;
                    $orderToProduct->product = $productId;
                }
                $orderToProduct->quantity = $quantity;
                $orderToProduct->price = isset($postPriceArray[$productId]) ? $postPriceArray[$productId] : 0.00;
                $orderToProduct->save();
            }
        }

        if (Yii::$app->request->post('save') === 'Y' && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $orderToProduct = OrderToProduct::find()->where(['order' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$orderToProduct) {
                $quantity = $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
                $price = $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->price: 0.00;
               
                $orderToProduct = new OrderToProduct();
                $orderToProduct->product = Yii::$app->request->post('addProduct');
                $orderToProduct->order = $model->id;
                $orderToProduct->quantity = $quantity;
                $orderToProduct->price = $price;
                $orderToProduct->save();
            }
        }

        $orderToProducts = $model->getOrderToProductsAll($id);

        if (Yii::$app->request->post('remove')) {
            foreach ($orderToProducts as $index => $orderToProduct) {
                if ($orderToProduct->product == Yii::$app->request->post('remove')) {
                    unset($orderToProducts[$index]);
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $orderToProducts,
            'sort' => false,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());

        $postQuantityArray = Yii::$app->request->post('quantity');
        $postPriceArray = Yii::$app->request->post('price');


        if (Yii::$app->request->post('remove')) {
            $orderToProduct = OrderToProduct::find()->where(['order' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($orderToProduct) {
                $orderToProduct->delete();
            }
        }

        if (is_array($postQuantityArray) && is_array($postPriceArray)) {
            foreach ($postQuantityArray as $productId => $quantity) {
                $orderToProduct = OrderToProduct::find()->where(['order' => $model->id, 'product' => $productId])->one();
                if ($orderToProduct) {
                    $orderToProduct->quantity = $quantity;
                    $orderToProduct->price = isset($postPriceArray[$productId])? $postPriceArray[$productId]: 0.00;
                    $orderToProduct->save();
                }
            }
        }
        
        if ($model->save() && Yii::$app->request->post('save') === 'Y') {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $orderToProduct = OrderToProduct::find()->where(['order' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$orderToProduct) {
                $quantity = $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
                $price = $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getQuotationOne()->getQuotationToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->price: 0.00;
               
                $orderToProduct = new OrderToProduct();
                $orderToProduct->product = Yii::$app->request->post('addProduct');
                $orderToProduct->order = $model->id;
                $orderToProduct->quantity = $quantity;
                $orderToProduct->price = $price;
                $orderToProduct->save();
            }
        }

        $productQuery = $model->getOrderToProducts();

        $dataProvider = new ActiveDataProvider([
            'query' => $productQuery,
            'sort' => false,
        ]);

        
        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
