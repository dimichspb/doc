<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Quotation;
use app\models\Request;
use app\models\Supplier;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Quotation::getStatusArray()) ?>

    <?= $form->field($model, 'expire_at')->widget(DatePicker::classname(), [
        'options' => [
            'class' => 'form-control'
        ],
    ]) ?>

    <?= $form->field($model, 'request')->dropDownList(ArrayHelper::map(Request::getActiveAll(), 'id', 'name')) ?>

    <?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Supplier::getActiveAll(), 'id', 'name')) ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
