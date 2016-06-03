<?php

namespace app\controllers;

use Yii;
use app\models\Quotation;
use app\models\QuotationSearch;
use app\models\QuotationToProduct;
use app\models\Request;
use yii\base\ErrorException;
use yii\data\ArrayDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\data\ActiveDataProvider;
use yii\base\InvalidParamException;

/**
 * QuotationController implements the CRUD actions for Quotation model.
 */
class QuotationController extends Controller
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
     * Lists all Quotation models.
     * @param $id
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new QuotationSearch();

        if ($id) {
            $searchModel->request = $id;
        } else {
            $searchModel->status = Quotation::STATUS_ACTIVE;
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Quotation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $productQuery = $model->getQuotationToProducts();

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
     * Creates a new Quotation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @throws ForbiddenHttpException
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $quotationPostData = Yii::$app->request->post('Quotation');

        if (isset($quotationPostData['id']) && $quotationPostData['id']>0) {
            $model = $this->findModel($quotationPostData['id']);
        } else {
            $model = new Quotation();
        }

        if ($id) {
            if (!Request::find()->where(['id' => $id])->exists()) {
                $message = "Извините, нет подходящих запросов";
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['index']);
            } elseif (
                    Request::findOne(['id' => $id])->status === Request::STATUS_INACTIVE ||
                    Request::findOne(['id' => $id])->status === Request::STATUS_DELETED
                )
            {
                throw new ForbiddenHttpException('Нельзя создавать предложение на некативные или удаленные запросы');
            }
        } else {
            $firstRequest = Request::getFirst();
            $id = $firstRequest->id;
        }
        $model->request = $id;
        $model->load(Yii::$app->request->post());

        $quotationToProducts = $model->getQuotationToProductsAll($id);

        $postQuantityArray = Yii::$app->request->post('quantity');
        $postPriceArray = Yii::$app->request->post('price');

        if (is_array($postQuantityArray) && is_array($postPriceArray)) {
            $model->save();
            foreach ($postQuantityArray as $productId => $quantity) {
                $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => $productId])->one();
                if (!$quotationToProduct) {
                    $quotationToProduct = new QuotationToProduct();
                    $quotationToProduct->quotation = $model->id;
                    $quotationToProduct->product = $productId;
                }
                $quotationToProduct->quantity = $quantity;
                $quotationToProduct->price = isset($postPriceArray[$productId]) ? $postPriceArray[$productId] : 0.00;
                $quotationToProduct->save();
            }
        }

        if (Yii::$app->request->post('remove')) {
            $model->save();
            foreach ($quotationToProducts as $index => $quotationToProduct) {
                if ($quotationToProduct->product == Yii::$app->request->post('remove')) {
                    QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => $quotationToProduct->product])->one()->delete();
                    unset($quotationToProducts[$index]);
                }
            }
        }

        if (Yii::$app->request->post('save') === 'Y' && $model->save()) {
            if (count($model->getQuotationToProductsAll()) && $order = $model->createOrder()) {
                $order->send();
                Yii::$app->session->addFlash('info', "На основании этого предложения был автоматически сгенерирован заказ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Счет на оплату заказа был автоматически отправлен клиенту");
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$quotationToProduct) {
                $quantity = $model->getRequestOne()->getRequestToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getRequestOne()->getRequestToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
                $quotationToProduct = new QuotationToProduct();
                $quotationToProduct->product = Yii::$app->request->post('addProduct');
                $quotationToProduct->quotation = $model->id;
                $quotationToProduct->quantity = $quantity;
                $quotationToProduct->price = 0.00;
                $quotationToProduct->save();
            }
            $quotationToProducts = $model->getQuotationToProductsAll();
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $quotationToProducts,
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
     * Updates an existing Quotation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $request
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());

        $postQuantityArray = Yii::$app->request->post('quantity');
        $postPriceArray = Yii::$app->request->post('price');


        if (Yii::$app->request->post('remove')) {
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($quotationToProduct) {
                $quotationToProduct->delete();
            }
        }

        if (is_array($postQuantityArray) && is_array($postPriceArray)) {
            foreach ($postQuantityArray as $productId => $quantity) {
                $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => $productId])->one();
                if ($quotationToProduct) {
                    $quotationToProduct->quantity = $quantity;
                    $quotationToProduct->price = isset($postPriceArray[$productId])? $postPriceArray[$productId]: 0.00;
                    $quotationToProduct->save();
                }
            }
        }
        
        if (Yii::$app->request->post('save') === 'Y' && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$quotationToProduct) {
                $quantity = $model->getRequestOne()->getRequestToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getRequestOne()->getRequestToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
                $quotationToProduct = new QuotationToProduct();
                $quotationToProduct->product = Yii::$app->request->post('addProduct');
                $quotationToProduct->quotation = $model->id;
                $quotationToProduct->quantity = $quantity;
                $quotationToProduct->price = 0.00;
                $quotationToProduct->save();
            }
        }

        $productQuery = $model->getQuotationToProducts();

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
     * Deletes an existing Quotation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Quotation::STATUS_DELETED;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Quotation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Quotation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Quotation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
