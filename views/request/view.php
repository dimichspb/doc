<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\Product;
use app\models\RequestToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить эту запись?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <p>
                <?= Html::a('Предложения', ['quotations/' . $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Создать предложение', ['quotation/create/' . $model->id], ['class' => 'btn btn-primary']) ?>
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
                'value' => $model->getCustomerName(),
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
            'price',
        ],
    ]) ?>

</div>
