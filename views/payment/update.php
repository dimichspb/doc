<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Изменить Оплату: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
