<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use app\models\OrderToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="order-view">
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот заказ?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('Распечатать', ['print', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
            </p>
        </div>
        <div class="col-md-6 text-right">
            <p>
                <?= Html::a('Оплаты', ['payments/' . $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Отгрузки', ['deliveries/' . $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Добавить оплату', ['payment/create/' . $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Добавить отгрузку', ['delivery/create/' . $model->id], ['class' => 'btn btn-primary']) ?>  
            </p>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            'created_at:date',
            //'expire_at:date',
            [
                'attribute' => 'seller',
                'format' => 'raw',
                'value' => Html::a($model->getSellerFullname(), Url::to(['entity/' . $model->seller])),
            ],
            [
                'attribute' => 'customer',
                'format' => 'raw',
                'value' => Html::a($model->getQuotationOne()->getRequestOne()->getCustomerName(), Url::to(['customer/' . $model->getQuotationOne()->getRequestOne()->getCustomerOne()->id])),
            ],
            [
                'attribute' => 'entity',
                'format' => 'raw',
                'value' => Html::a($model->getQuotationOne()->getRequestOne()->getEntityName(), Url::to(['entity/' . $model->getQuotationOne()->getRequestOne()->getEntityOne()->id])),
            ],
            [
                'attribute' => 'request',
                'format' => 'raw',
                'value' => Html::a($model->getQuotationOne()->getRequestName(), Url::to(['request/' . $model->getQuotationOne()->request])),
            ],
            [
                'attribute' => 'quotation',
                'format' => 'raw',
                'value' => Html::a($model->getQuotationName(), Url::to(['quotation/' . $model->quotation])),
            ],
            [
                'attribute' => 'amount',
                'value' => $model->getAmount(),
                'format' => 'currency',
            ],
            [
                'attribute' => 'paidAmount',
                'value' => $model->getPaidAmount(),
                'format' => 'currency',
            ],
            
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->thread;
                },
            ],
            'quantity',
            'price:currency',
        ],
    ]) ?>

</div>
