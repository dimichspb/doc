<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */

$this->title = 'Update Entity Person Role: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Entity Person Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="entity-person-role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
