<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\QuotationToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Предложение: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Предложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить это предложение?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <p>
                <?= Html::a('Заказы', ['orders/' . $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Разместить заказ', ['order/create/' . $model->id], ['class' => 'btn btn-primary']) ?>
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
                'attribute' => 'request',
                'format' => 'raw',
                'value' => Html::a($model->getRequestName(), Url::to(['request/' . $model->request])),
            ],
            [
                'attribute' => 'supplier',
                'value' => Html::a($model->getSupplierName(), Url::to(['supplier/' . $model->supplier])),
                'format' => 'raw',
            ],
        ],
    ]) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->thread;
                },
            ],
            'quantity',
            'price:currency',
        ],
    ]) ?>

</div>
