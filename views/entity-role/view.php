<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntityRole */
/* @var $entity \app\models\Entity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Должности', 'url' => ['roles/' . $entity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-role-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['roles/' . $entity->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту должность?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'name',
        ],
    ]) ?>

</div>
