<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Request;
use app\models\Customer;
use app\models\Product;
use app\models\Entity;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">
    <p>
        <?= Html::a('Создать запрос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'requests-container']); ?>    <?= GridView::widget([
        'id' => 'requests-grid-view',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (Request $model) {
                    return $model->getStatusName();
                },
                'filter' => Request::getStatusArray(),
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
            [
                'attribute' => 'customer',
                'value' => function (Request $model) {
                    return Html::a($model->getCustomerName(), Url::to(['customer/' . $model->customer]));
                },
                'format' => 'raw',
                'filter' => ArrayHelper::map(Customer::getActiveAll(), 'id', 'name'),
                'visible' => Yii::$app->user->can('getCustomerDetails'),
            ],
            [
                'attribute' => 'entity',
                'value' => function (Request $model) {
                    return Html::a($model->getEntityName(), Url::to(['entity/' . $model->entity]));
                },
                'format' => 'raw',
                'filter' => ArrayHelper::map(Entity::getActiveAll(), 'id', 'name'),
                'visible' => Yii::$app->user->can('getEntityDetails'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {update}  {delete}  {quotation}  {quotations}',
                'buttons' => [
                    'quotation' => function ($url, Request $model) {
                        if ($model->status === Request::STATUS_ACTIVE || $model->status === Request::STATUS_QUOTED) {
                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', ['quotation/create/' . $model->id]);
                        }
                    },
                    'quotations' => function ($url, Request $model) {
                        if ($model->status === Request::STATUS_QUOTED) {
                            return Html::a('<span class="glyphicon glyphicon-list"></span>', ['quotations/' . $model->id]);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
