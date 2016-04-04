<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true]) ?>

    <?= $form->field($model, 'role')->listBox(User::getAllRoles(), ['multiple'=>'multiple']) ?>

    <?= $form->field($model, 'status')->dropDownList(User::getStatusArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
