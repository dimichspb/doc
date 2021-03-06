<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */
/* @var $entity \app\models\Entity */

$this->title = $model->getFull();
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['/entities']];
$this->params['breadcrumbs'][] = ['label' => $entity->getFull(), 'url' => ['/entity/view', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['index', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-person-role-view">
    <p>
        <?= Html::a('Список', ['/employees/' . $entity->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить данного сотрудника?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'role',
                'value' => $model->getRoleName(),
            ],
            [
                'attribute' => 'person',
                'value' => $model->getPersonFullname(),
            ],
        ],
    ]) ?>

</div>
