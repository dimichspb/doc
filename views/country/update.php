<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = 'Изменить страну: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'страны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="country-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
