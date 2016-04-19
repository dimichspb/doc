<?php

namespace app\controllers;

use Yii;
use app\models\Delivery;
use app\models\DeliverySearch;
use app\models\DeliveryToProduct;
use app\models\Order;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;

/**
 * DeliveryController implements the CRUD actions for Delivery model.
 */
class DeliveryController extends Controller
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
        ];
    }

    /**
     * Lists all Delivery models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $searchModel = new DeliverySearch();
        
        $searchModel->status = Delivery::STATUS_ACTIVE;
        
        if ($id) {
            $searchModel->order = $id;
        }
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Delivery model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $productQuery = $model->getDeliveryToProducts();

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
     * Creates a new Delivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
        public function actionCreate($id = null)
    {
        $deliveryPostData = Yii::$app->request->post('Delivery');

        if (isset($deliveryPostData['id']) && $deliveryPostData['id']>0) {
            $model = $this->findModel($deliveryPostData['id']);
        } else {
            $model = new Delivery();
        }
        
        if ($id) {
            if (
                Order::findOne(['id' => $id])->status === Order::STATUS_INACTIVE ||
                Order::findOne(['id' => $id])->status === Order::STATUS_DELETED) {
                throw new ForbiddenHttpException('Нельзя создавать отгрузки на некативные или удаленные заказы');
            }
        } else {
            $firstOrder = Order::getFirst();

            if (!$firstOrder) {
                $message = "Извините, нет подходящих заказов";
                Yii::$app->session->setFlash('error', $message);
                return $this->redirect(['index']);
            }
            $id = $firstOrder->id;
        }
        $model->order = $id;

        $model->load(Yii::$app->request->post());

        $postQuantityArray = Yii::$app->request->post('quantity');

        if (is_array($postQuantityArray)) {
            $model->save();
            foreach ($postQuantityArray as $productId => $quantity) {
                $deliveryToProduct = DeliveryToProduct::find()->where(['delivery' => $model->id, 'product' => $productId])->one();
                if (!$deliveryToProduct) {
                    $deliveryToProduct = new DeliveryToProduct();
                    $deliveryToProduct->delivery = $model->id;
                    $deliveryToProduct->product = $productId;
                }
                $deliveryToProduct->quantity = $quantity;
                $deliveryToProduct->save();
            }
        }

        if (Yii::$app->request->post('save') === 'Y' && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $deliveryToProduct = DeliveryToProduct::find()->where(['delivery' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$deliveryToProduct) {
                $quantity = $model->getOrderOne()->getOrderToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getOrderOne()->getOrderToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
               
                $deliveryToProduct = new DeliveryToProduct();
                $deliveryToProduct->product = Yii::$app->request->post('addProduct');
                $deliveryToProduct->delivery = $model->id;
                $deliveryToProduct->quantity = $quantity;
                $deliveryToProduct->save();
            }
        }

        $deliveryToProducts = $model->getDeliveryToProductsAll($id);

        if (Yii::$app->request->post('remove')) {
            foreach ($deliveryToProducts as $index => $deliveryToProduct) {
                if ($deliveryToProduct->product == Yii::$app->request->post('remove')) {
                    unset($deliveryToProducts[$index]);
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $deliveryToProducts,
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
     * Updates an existing Delivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());

        $postQuantityArray = Yii::$app->request->post('quantity');

        if (Yii::$app->request->post('remove')) {
            $deliveryToProduct = DeliveryToProduct::find()->where(['delivery' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($deliveryToProduct) {
                $deliveryToProduct->delete();
            }
        }

        if (is_array($postQuantityArray)) {
            foreach ($postQuantityArray as $productId => $quantity) {
                $deliveryToProduct = DeliveryToProduct::find()->where(['delivery' => $model->id, 'product' => $productId])->one();
                if ($deliveryToProduct) {
                    $deliveryToProduct->quantity = $quantity;
                    $deliveryToProduct->save();
                }
            }
        }
        
        if ($model->save() && Yii::$app->request->post('save') === 'Y') {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $deliveryToProduct = DeliveryToProduct::find()->where(['delivery' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$deliveryToProduct) {
                $quantity = $model->getOrderOne()->getOrderToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->exists()? 
                    $model->getOrderOne()->getOrderToProducts()->where(['product' => Yii::$app->request->post('addProduct')])->one()->quantity: 0;
                 
                $deliveryToProduct = new DeliveryToProduct();
                $deliveryToProduct->product = Yii::$app->request->post('addProduct');
                $deliveryToProduct->delivery = $model->id;
                $deliveryToProduct->quantity = $quantity;
                $deliveryToProduct->save();
            }
        }

        $productQuery = $model->getDeliveryToProducts();

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
     * Deletes an existing Delivery model.
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
     * Finds the Delivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Delivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Delivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
