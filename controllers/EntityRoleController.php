<?php

namespace app\controllers;

use Yii;
use app\models\EntityRole;
use app\models\EntityRoleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Entity;

/**
 * EntityRoleController implements the CRUD actions for EntityRole model.
 */
class EntityRoleController extends Controller
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
     * Lists all EntityRole models.
     * @param $id
     * @return mixed
     */
    public function actionIndex($id)
    {
        $searchModel = new EntityRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $entity = Entity::findById($id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'entity' => $entity,
        ]);
    }

    /**
     * Displays a single EntityRole model.
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
     * Creates a new EntityRole model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $id
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new EntityRole();
        $model->entity = $id;
        $entity = Entity::findById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->link('entity', $entity);
            $referrer = Yii::$app->request->post('referrer');
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
     * Updates an existing EntityRole model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $entity = $model->getEntityOne();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $referrer = Yii::$app->request->post('referrer');
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
     * Deletes an existing EntityRole model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $entity = $model->getEntityOne();
        $model->delete();

        return $this->redirect(['roles/' . $entity->id]);
    }

    /**
     * Finds the EntityRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EntityRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EntityRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
