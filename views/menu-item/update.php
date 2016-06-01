<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MenuItem */

$this->title = 'Update Menu Item: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="menu-item-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
