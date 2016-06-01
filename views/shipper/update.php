<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Shipper */

$this->title = 'Изменить переозчика: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Перевозчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="shipper-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
