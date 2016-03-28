<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Request;
use app\models\Customer;
use app\models\Product;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="request-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Request::getStatusArray()) ?>
    
    <?= $form->field($model, 'customer')->dropDownList(ArrayHelper::map(Customer::getActiveAll(), 'id', 'name')) ?>

    <?= $form->field($model, 'product')->dropDownList(ArrayHelper::map(Product::getActiveAll(), 'id', 'fullname')) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
