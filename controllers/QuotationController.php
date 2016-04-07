<?php

namespace app\controllers;

use Yii;
use app\models\Quotation;
use app\models\QuotationSearch;
use app\models\QuotationToProduct;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

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
        ];
    }

    /**
     * Lists all Quotation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QuotationSearch();

        $searchModel->status = Quotation::STATUS_ACTIVE;

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
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new Quotation();

        if ($id) {
            $model->request = $id;
        }

        $quotationPostData = Yii::$app->request->post('Request');

        if (isset($quotationPostData['id']) && $quotationPostData['id']>0) {
            $model = $this->findModel($quotationPostData['id']);
        } else {
            $model = new Quotation();
        }

        $model->load(Yii::$app->request->post());

        if (is_array(Yii::$app->request->post('quantity'))) {
            $model->save();
            foreach (Yii::$app->request->post('quantity') as $productId => $quantity) {
                $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => $productId])->one();
                if ($quotationToProduct) {
                    $quotationToProduct->product = $productId;
                    $quotationToProduct->quotation = $model->id;
                    $quotationToProduct->quantity = $quantity;
                    $quotationToProduct->save();
                }
            }
        }

        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $model->save();
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$quotationToProduct) {
                $quotationToProduct = new QuotationToProduct();
                $quotationToProduct->product = Yii::$app->request->post('addProduct');
                $quotationToProduct->quotation = $model->id;
                $quotationToProduct->quantity = 0;
                $quotationToProduct->save();
            }
        }

        if (Yii::$app->request->post('remove')) {
            $model->save();
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($quotationToProduct) {
                $quotationToProduct->delete();
            }
        }

        if (Yii::$app->request->post('save') === 'Y') {
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $productQuery = $model->getQuotationToProducts();

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
     * Updates an existing Quotation model.
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
                $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => $productId])->one();
                if ($quotationToProduct) {
                    $quotationToProduct->quantity = $quantity;
                    $quotationToProduct->save();
                }
            }
        }

        if (Yii::$app->request->post('add') === 'Y' && Yii::$app->request->post('addProduct')) {
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('addProduct')])->one();
            if (!$quotationToProduct) {
                $quotationToProduct = new QuotationToProduct();
                $quotationToProduct->product = Yii::$app->request->post('addProduct');
                $quotationToProduct->quotation = $model->id;
                $quotationToProduct->save();
            }
        }

        if (Yii::$app->request->post('remove')) {
            $quotationToProduct = QuotationToProduct::find()->where(['quotation' => $model->id, 'product' => Yii::$app->request->post('remove')])->one();
            if ($quotationToProduct) {
                $quotationToProduct->delete();
            }
        }

        $productQuery = $model->getQuotationToProducts();

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
