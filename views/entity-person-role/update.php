<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */
/* @var $entity \app\models\Entity */

$this->title = 'Изменить сотрудника: ' . $model->getFull();
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники ' . $entity->getFull(), 'url' => ['employees/' . $entity->id]];
$this->params['breadcrumbs'][] = ['label' => $model->getFull(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="entity-person-role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'entity' => $entity,
    ]) ?>

</div>
