<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Price;

/* @var $this yii\web\View */
/* @var $model app\models\Price */

$this->title = $model->getProductFullname();
$this->params['breadcrumbs'][] = ['label' => 'Цены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-view">

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
            //'id',
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            //'created_at',
            //'updated_at',
            'started_at:date',
            'expire_at:date',
            [
                'attribute' => 'product',
                'value' => $model->getProductFullname(),
            ],
            [
                'attribute' => 'supplier',
                'value' => $model->getSupplierName(),
            ],
            'quantity',
            'value:currency',
        ],
    ]) ?>

</div>
