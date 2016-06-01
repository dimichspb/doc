<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Shipper */

$this->title = 'Добавить Перевозчика';
$this->params['breadcrumbs'][] = ['label' => 'Перевозчики', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipper-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
