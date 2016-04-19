<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Оплата: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-8">
            <p>
                <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить эту оплату?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        </div>
        <div class="col-md-4 text-right">
            <p>
                
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
                'attribute' => 'order',
                'format' => 'raw',
                'value' => Html::a($model->getOrderName(), Url::to(['order/' . $model->order])),
            ],
            [
                'attribute' => 'entity',
                'format' => 'raw',
                'value' => Html::a($model->getEntityName(), Url::to(['entity/' . $model->entity])),
            ],
            'amount:currency',
        ],
    ]) ?>

</div>
