<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */

$this->title = $model->full;
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-view">

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
            //'created_by',
            [
                'attribute' => 'entity_form',
                'value' => $model->getEntityFormName(),
            ],
            'name',
            'fullname',
            'ogrn',
            'inn',
            'kpp',
            [
                'attribute' => 'address',
                'format' => 'raw',
                'value' => Html::a($model->getAddressFull(), Url::to(['address/' . $model->address])),
            ],
            [
                'attribute' => 'factaddress',
                'format' => 'raw',
                'value' => Html::a($model->getFactAddressFull(), Url::to(['address/' . $model->factaddress])),
            ],
            [
                'attribute' => 'account',
                'format' => 'raw',
                'value' => Html::a($model->getAccountFull(), Url::to(['account/' . $model->account])),
            ],
            [
                'attribute' => 'director',
                'format' => 'raw',
                'value' => Html::a($model->getDirectorFull(), Url::to(['employee/' . $model->director])),
            ],
            [
                'attribute' => 'accountant',
                'format' => 'raw',
                'value' => Html::a($model->getAccountantFull(), Url::to(['employee/' . $model->accountant])),
            ],
        ],
    ]) ?>

</div>
