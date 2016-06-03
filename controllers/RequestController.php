<?php

namespace app\controllers;

use app\models\Product;
use app\models\ProductSearch;
use app\models\RequestToProduct;
use Yii;
use app\models\Request;
use app\models\RequestSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
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
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();

        $searchModel->status = Request::STATUS_ACTIVE;

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $productQuery = $model->getRequestToProducts();

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
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $requestPostData = Yii::$app->request->post('Request');

        if (isset($requestPostData['id']) && $requestPostData['id']>0) {
            $model = $this->findModel($requestPostData['id']);
        } else {
            $model = new Request();
        }

        $model->load(Yii::$app->request->post());
        
        $postQuantityArray = Yii::$app->request->post('quantity');

        if (is_array($postQuantityArray)) {
            $model->save();
            foreach ($postQuantityArray as $productId => $quantity) {
                $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => $productId])->one();
                if ($requestToProduct) {
                    $requestToProduct->quantity = $quantity;
                    $requestToProduct->save();
                }
            }
        }

        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$requestToProduct) {
                $requestToProduct = new RequestToProduct();
                $requestToProduct->product = Yii::$app->request->post('addProduct');
                $requestToProduct->request = $model->id;
                $requestToProduct->quantity = 0;
                $requestToProduct->save();
            }
        }

        if (Yii::$app->request->post('save') === 'Y') {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $productQuery = $model->getRequestToProducts();

        if (Yii::$app->request->post('remove')) {
            $model->save();
            $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($requestToProduct) {
                $requestToProduct->delete();
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $productQuery,
            'sort' => false,
        ]);

        return $this->render('create', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->load(Yii::$app->request->post());

        if (is_array(Yii::$app->request->post('quantity'))) {
            foreach (Yii::$app->request->post('quantity') as $productId => $quantity) {
                $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => $productId])->one();
                if ($requestToProduct) {
                    $requestToProduct->quantity = $quantity;
                    $requestToProduct->save();
                }
            }
        }

        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$requestToProduct) {
                $requestToProduct = new RequestToProduct();
                $requestToProduct->product = Yii::$app->request->post('addProduct');
                $requestToProduct->request = $model->id;
                $requestToProduct->save();
            }
        }

        if (Yii::$app->request->post('remove')) {
            $requestToProduct = RequestToProduct::find()->where(['request' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($requestToProduct) {
                $requestToProduct->delete();
            }
        }

        $productQuery = $model->getRequestToProducts();

        $dataProvider = new ActiveDataProvider([
            'query' => $productQuery,
            'sort' => false,
        ]);

        if ($model->save() && Yii::$app->request->post('save') === 'Y') {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Request::STATUS_DELETED;
        $model->save(false);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
