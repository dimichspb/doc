<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntityRole */
/* @var $entity \app\models\Entity */

$this->title = 'Изменить должность';
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['/entities']];
$this->params['breadcrumbs'][] = ['label' => $entity->getFull(), 'url' => ['/entity/view', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = ['label' => 'Должности', 'url' => ['roles/' . $entity->id]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="entity-role-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
