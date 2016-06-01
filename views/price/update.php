<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Price */

$this->title = 'Изменить цену: ' . $model->getProductFullname();
$this->params['breadcrumbs'][] = ['label' => 'Цены', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getProductFullname(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="price-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
