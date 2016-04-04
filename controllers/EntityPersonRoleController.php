<?php

namespace app\controllers;

use app\models\Entity;
use Yii;
use app\models\EntityPersonRole;
use app\models\EntityPersonRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EntityPersonRoleController implements the CRUD actions for EntityPersonRole model.
 */
class EntityPersonRoleController extends Controller
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
     * Lists all EntityPersonRole models.
     * @param $id
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new EntityPersonRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['entity' => $id]);

        $entity = Entity::findById($id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'entity' => $entity,
        ]);
    }

    /**
     * Displays a single EntityPersonRole model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $entity = $model->getEntityOne();

        return $this->render('view', [
            'model' => $model,
            'entity' => $entity,
        ]);
    }

    /**
     * Creates a new EntityPersonRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new EntityPersonRole();
        $model->entity = $id;

        $entity = Entity::findById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->link('entity', $entity);
            $referrer = Yii::$app->request->get('referrer')? urldecode(Yii::$app->request->get('referrer')): Yii::$app->request->post('referrer');
            if (!empty($referrer)) {
                return $this->redirect($referrer);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'entity' => $entity,
            ]);
        }
    }

    /**
     * Updates an existing EntityPersonRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $entity = $model->getEntityOne();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $referrer = Yii::$app->request->get('referrer')? urldecode(Yii::$app->request->get('referrer')): Yii::$app->request->post('referrer');
            if (!empty($referrer)) {
                return $this->redirect($referrer);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'entity' => $entity,
            ]);
        }
    }

    /**
     * Deletes an existing EntityPersonRole model.
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
     * Finds the EntityPersonRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntityPersonRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntityPersonRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
