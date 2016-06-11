<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Product;
use app\models\RequestToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Запрос: ' . $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">
    <div class="row">
        <div class="col-md-6">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этот запрос?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-6 text-right">
            <p>
                <?= $model->getOrders()->exists()? Html::a('Заказы', ['/orders', 'request' => $model->id], ['class' => 'btn btn-success']): '' ?>
                <?= $model->getQuotations()->exists()? Html::a('Предложения', ['/quotations/' . $model->id], ['class' => 'btn btn-success']): '' ?>
                <?= Html::a('Создать предложение', ['/quotation/create/' . $model->id], ['class' => 'btn btn-primary']) ?>
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
            [
                'attribute' => 'customer',
                'value' => Html::a($model->getCustomerName(), Url::to(['customer/' . $model->customer])),
                'format' => 'raw',
                'visible' => Yii::$app->user->can('getCustomerDetails'),
            ],
            [
                'attribute' => 'entity',
                'value' => Html::a($model->getEntityName(), Url::to(['entity/' . $model->entity])),
                'format' => 'raw',
                'visible' => Yii::$app->user->can('getEntityDetails'),
            ],
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->thread;
                },
            ],
            'quantity',
            [
                'attribute' => 'price',
                'format' => 'currency',
                'value' => function (RequestToProduct $requestToProduct) use ($model){
                    return $requestToProduct->getProductOne()->getValidPriceValue($model->created_at);
                },
            ],
        ],
    ]) ?>

</div>
