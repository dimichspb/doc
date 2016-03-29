<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */

$this->title = $model->full;
$this->params['breadcrumbs'][] = ['label' => 'Юр. лица', 'url' => ['index']];
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
                'value' => $model->getAddressFull(),
            ],
            [
                'attribute' => 'factaddress',
                'value' => $model->getFactAddressFull(),
            ],
            [
                'attribute' => 'account',
                'value' => $model->getAccountFull(),
            ],
            [
                'attribute' => 'director',
                'value' => $model->getDirectorFull(),
            ],
            [
                'attribute' => 'accountant',
                'value' => $model->getAccountantFull(),
            ],
        ],
    ]) ?>

</div>
