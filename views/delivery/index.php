<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\models\Delivery;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отгрузки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить отгрузку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function (Delivery $delivery) {
                    return $delivery->getStatusName();
                },
                'filter' => Delivery::getStatusArray(),
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
                'attribute' => 'delivered_at',
                'value' => 'delivered_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'delivered_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                ]),

            ],
            [
                'attribute' => 'order',
                'format' => 'raw',
                'value' => function (Delivery $delivery) {
                    return Html::a($delivery->getOrderName(), Url::to(['order/' . $delivery->order]));
                }
            ],
            [
                'attribute' => 'shipper',
                'format' => 'raw',
                'value' => function (Delivery $delivery) {
                    return Html::a($delivery->getShipperName(), Url::to(['shipper/' . $delivery->shipper]));
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
