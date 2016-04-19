<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\DeliveryToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Delivery */
/* @var $dataProvider DataProviderInterface */

$this->title = 'Отгрузка: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Отгрузки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить эту отгрузку?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-6 text-right">
            <p>
                
            </p>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            'created_at:date',
            'delivered_at:date',
            [
                'attribute' => 'order',
                'format' => 'raw',
                'value' => Html::a($model->getOrderName(), Url::to(['order/' . $model->order])),
            ],
            [
                'attribute' => 'shipper',
                'format' => 'raw',
                'value' => Html::a($model->getShipperName(), Url::to(['shipper/' . $model->shipper])),
            ],
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->thread;
                },
            ],
            'quantity',
        ],
    ]) ?>

</div>
