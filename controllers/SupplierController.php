<?php

namespace app\controllers;

use app\models\Entity;
use app\models\SupplierToEntity;
use Yii;
use app\models\Supplier;
use app\models\SupplierSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\AccessRule;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
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
     * Lists all Supplier models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Supplier model.
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
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $supplierPostData = Yii::$app->request->post('Supplier');

        if (isset($supplierPostData['id']) && $supplierPostData['id']>0) {
            $model = $this->findModel($supplierPostData['id']);
        } else {
            $model = new Supplier();
        }

        $model->load(Yii::$app->request->post());

        $postRemoveId = Yii::$app->request->post('remove');

        if ($postRemoveId) {
            $model->save();
            $supplierToEntity = $model->getSupplierToEntities()->where(['entity' => $postRemoveId])->one();
            $supplierToEntity->delete();
        }

        if (Yii::$app->request->post('add') == 'Y') {
            $model->save();
            $postAddEntity = Yii::$app->request->post('addEntity');
            $entityToAdd = Entity::findById($postAddEntity);
            if ($entityToAdd && !$model->getEntities()->where(['id' => $postAddEntity])->exists()) {
                $supplierToEntity = new SupplierToEntity();
                $supplierToEntity->entity = $entityToAdd->id;
                $supplierToEntity->supplier = $model->id;
                $supplierToEntity->save();
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
     * Updates an existing Supplier model.
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
            $supplierToEntity = $model->getSupplierToEntities()->where(['entity' => $postRemoveId])->one();
            $supplierToEntity->delete();
        }

        if (Yii::$app->request->post('add') == 'Y') {
            $postAddEntity = Yii::$app->request->post('addEntity');
            $entityToAdd = Entity::findById($postAddEntity);
            if ($entityToAdd && !$model->getEntities()->where(['id' => $postAddEntity])->exists()) {
                $supplierToEntity = new SupplierToEntity();
                $supplierToEntity->entity = $entityToAdd->id;
                $supplierToEntity->supplier = $model->id;
                $supplierToEntity->save();
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
     * Deletes an existing Supplier model.
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
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
