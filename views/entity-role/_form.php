<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntityRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entity-role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::hiddenInput('referrer', Yii::$app->request->referrer) ?>

    <?= Html::hiddenInput('entity', Yii::$app->request->get('id')) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
