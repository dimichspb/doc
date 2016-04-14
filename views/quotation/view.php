<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\QuotationToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Предложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
                'value' => $model->getRequestName(),
            ],
            [
                'attribute' => 'supplier',
                'value' => $model->getSupplierName(),
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
            'price',
        ],
    ]) ?>

</div>
