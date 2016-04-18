<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Quotation;
use app\models\Supplier;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Предложения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать предложение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'quotations-container']); ?>    <?= GridView::widget([
        'id' => 'quotations-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (Quotation $model) {
                    return $model->getStatusName();
                },
                'filter' => Quotation::getStatusArray(),
            ],
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                ]),

            ],
            /*[
                'attribute' => 'expire_at',
                'value' => 'expire_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'expire_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                ]),

            ],*/
            [
                'attribute' => 'request',
                'value' => function (Quotation $model) {
                    return Html::a($model->getRequestName(), Url::to(['request/' . $model->request]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'supplier',
                'value' => function (Quotation $model) {
                    return Html::a($model->getSupplierName(), Url::to(['supplier/' . $model->supplier]));
                },
                'format' => 'raw',
                'filter' => ArrayHelper::map(Supplier::getActiveAll(), 'id', 'name'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {update}  {delete}  {order}  {orders}',
                'buttons' => [
                    'order' => function ($url, Quotation $model) {
                        if ($model->status === Quotation::STATUS_ACTIVE || $model->status === Quotation::STATUS_ORDERED) {
                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', ['order/create/' . $model->id]);
                        }
                    },
                    'orders' => function ($url, Quotation $model) {
                        if ($model->status === Quotation::STATUS_ORDERED) {
                            return Html::a('<span class="glyphicon glyphicon-list"></span>', ['orders/' . $model->id]);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
