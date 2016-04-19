<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Payment */

$this->title = 'Добавить Оплату';
$this->params['breadcrumbs'][] = ['label' => 'Оплаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
