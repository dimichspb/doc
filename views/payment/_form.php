<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Order;
use app\models\Entity;

/* @var $this yii\web\View */
/* @var $model app\models\Payment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order')->dropDownList(ArrayHelper::map(Order::getActiveAndPaidAll(), 'id', 'name'), ['id' => 'payment-order-input']) ?>

    <?= $form->field($model, 'entity')->dropDownList(ArrayHelper::map(Entity::getActiveAll(), 'id', 'full'), ['id' => 'payment-entity-input']) ?>

    <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'value' => isset($model->amount)? $model->amount: '0.00', 'step' => '0.01', 'min' => '0.00']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
