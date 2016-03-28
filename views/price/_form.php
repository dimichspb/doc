<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Price */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="price-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusArray()) ?>

    <?= $form->field($model, 'started_at')->widget(DatePicker::classname(), [
        'options' => [
            'class' => 'form-control'
        ],
    ]) ?>

    <?= $form->field($model, 'expire_at')->widget(DatePicker::classname(), [
        'options' => [
            'class' => 'form-control'
        ],
    ]) ?>

    <?= $form->field($model, 'product')->dropDownList(yii\helpers\ArrayHelper::map(\app\models\Product::getActiveAll(), 'id', 'fullname')) ?>

    <?= $form->field($model, 'supplier')->dropDownList(\yii\helpers\ArrayHelper::map(\app\models\Supplier::find()->all(), 'id', 'name')) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'value')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
