<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use app\models\CustomerSearch;
use app\models\Entity;
use app\models\CustomerToEntity;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;
use yii\data\ActiveDataProvider;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
                        'roles' => ['Admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $entities = $model->getEntities();

        $dataProvider = new ActiveDataProvider([
            'query' => $entities,
            'sort' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $customerPostData = Yii::$app->request->post('Customer');

        if (isset($customerPostData['id']) && $customerPostData['id']>0) {
            $model = $this->findModel($customerPostData['id']);
        } else {
            $model = new Customer();
        }

        $model->load(Yii::$app->request->post());

        $postRemoveId = Yii::$app->request->post('remove');

        if ($postRemoveId) {
            $model->save();
            $customerToEntity = $model->getCustomerToEntities()->where(['entity' => $postRemoveId])->one();
            $customerToEntity->delete();
        }

        if (Yii::$app->request->post('add') == 'Y') {
            $model->save();
            $postAddEntity = Yii::$app->request->post('addEntity');
            $entityToAdd = Entity::findById($postAddEntity);
            if ($entityToAdd && !$model->getEntities()->where(['id' => $postAddEntity])->exists()) {
                $customerToEntity = new CustomerToEntity();
                $customerToEntity->entity = $entityToAdd->id;
                $customerToEntity->customer = $model->id;
                $customerToEntity->save();
            }
        }

        $entities = $model->getEntities();

        $dataProvider = new ActiveDataProvider([
            'query' => $entities,
            'sort' => false,
        ]);

        if (Yii::$app->request->post('save') == 'Y') {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->load(Yii::$app->request->post());

        $postRemoveId = Yii::$app->request->post('remove');

        if ($postRemoveId) {
            $customerToEntity = $model->getCustomerToEntities()->where(['entity' => $postRemoveId])->one();
            $customerToEntity->delete();
        }

        if (Yii::$app->request->post('add') == 'Y') {
            $postAddEntity = Yii::$app->request->post('addEntity');
            $entityToAdd = Entity::findById($postAddEntity);
            if ($entityToAdd && !$model->getEntities()->where(['id' => $postAddEntity])->exists()) {
                $customerToEntity = new CustomerToEntity();
                $customerToEntity->entity = $entityToAdd->id;
                $customerToEntity->customer = $model->id;
                $customerToEntity->save();
            }
        }

        $entities = $model->getEntities();

        $dataProvider = new ActiveDataProvider([
            'query' => $entities,
            'sort' => false,
        ]);

        if ($model->save() && Yii::$app->request->post('save') == 'Y') {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
