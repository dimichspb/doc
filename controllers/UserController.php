<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\Customer;
use app\models\Supplier;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $customersDataProvider = new ActiveDataProvider([
            'query' => $model->getCustomers(),
        ]);
        $suppliersDataProvider = new ActiveDataProvider([
            'query' => $model->getSuppliers(),
        ]);
        
        return $this->render('view', [
            'model' => $model,
            'customersDataProvider' => $customersDataProvider,
            'suppliersDataProvider' => $suppliersDataProvider,
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $model->status = User::STATUS_ACTIVE;
        
        $postUserData = Yii::$app->request->post();
        
        var_dump($postUserData);
        $model->load($postUserData);
        var_dump($model);
        if (Yii::$app->request->post('addCustomer') == 'Y') {
            if (!$model->getCustomers()->where(['id' => Yii::$app->request->post('customer')])->exists() && $model->save()) {
                $customer = Customer::findOne(['id' => Yii::$app->request->post('customer')]);
                $model->link('customers', $customer);
            }        
        }
        if (Yii::$app->request->post('addSupplier') == 'Y') {
            if (!$model->getSuppliers()->where(['id' => Yii::$app->request->post('supplier')])->exists() && $model->save()) {
                $supplier = Supplier::findOne(['id' => Yii::$app->request->post('supplier')]);
                $model->link('suppliers', $supplier);
            }        
        }
        
        $customersDataProvider = new ActiveDataProvider([
            'query' => $model->getCustomers(),
        ]);
        $suppliersDataProvider = new ActiveDataProvider([
            'query' => $model->getSuppliers(),
        ]);

        if (Yii::$app->request->post('save') == 'Y' && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //var_dump($model);
            //die();
            return $this->render('create', [
                'model' => $model,
                'customersDataProvider' => $customersDataProvider,
                'suppliersDataProvider' => $suppliersDataProvider,
                'allowEdit' => true,
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $postUserData = Yii::$app->request->post();
        $model->load($postUserData);
        
        if (Yii::$app->request->post('addCustomer') == 'Y') {
            if (!$model->getCustomers()->where(['id' => Yii::$app->request->post('customer')])->exists() && $model->save()) {
                $customer = Customer::findOne(['id' => Yii::$app->request->post('customer')]);
                $model->link('customers', $customer);
            }        
        }
        if (Yii::$app->request->post('addSupplier') == 'Y') {
            if (!$model->getSuppliers()->where(['id' => Yii::$app->request->post('supplier')])->exists() && $model->save()) {
                $supplier = Supplier::findOne(['id' => Yii::$app->request->post('supplier')]);
                $model->link('suppliers', $supplier);
            }        
        }
        
        $customersDataProvider = new ActiveDataProvider([
            'query' => $model->getCustomers(),
        ]);
        $suppliersDataProvider = new ActiveDataProvider([
            'query' => $model->getSuppliers(),
        ]);

        if (Yii::$app->request->post('save') == 'Y' && $model->save()) {
            return $this->redirect(['view', 'id' => $id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'customersDataProvider' => $customersDataProvider,
                'suppliersDataProvider' => $suppliersDataProvider,
                'allowEdit' => false,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
