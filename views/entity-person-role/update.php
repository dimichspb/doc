<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */
/* @var $entity \app\models\Entity */

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['/entities']];
$this->params['breadcrumbs'][] = ['label' => $entity->getFull(), 'url' => ['/entity/view', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['employees/' . $entity->id]];
$this->params['breadcrumbs'][] = ['label' => $model->getFull(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="entity-person-role-update">
    <?= $this->render('_form', [
        'model' => $model,
        'entity' => $entity,
    ]) ?>
</div>
