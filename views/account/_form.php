<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Bank;

/* @var $this yii\web\View */
/* @var $model app\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::hiddenInput('referrer', Yii::$app->request->referrer) ?>

    <?= Html::hiddenInput('entity', Yii::$app->request->get('id')) ?>

    <?= $form->field($model, 'bank')->dropDownList(Bank::getBankArray()) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
