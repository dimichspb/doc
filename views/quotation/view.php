<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */

$this->title = $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Предложения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
            //'updated_at',
            'expire_at:date',
            [
                'attribute' => 'request',
                'value' => $model->getRequestName(),
            ],
            [
                'attribute' => 'supplier',
                'value' => $model->getSupplierName(),
            ],
            'value:currency',
        ],
    ]) ?>

</div>
