<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Entity;
use app\models\EntityForm;
use app\models\Address;
use app\models\Account;
use app\models\EntityPersonRole;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="entity-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'yii\bootstrap\ActiveField']); ?>

    <?= $form->field($model, 'status')->dropDownList(Entity::getStatusArray()) ?>

    <?= $form->field($model, 'entity_form')->dropDownList(EntityForm::getEntityFormsArray()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address', [
            'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
                Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['address/update/' . (string)$model->address], ['class' => 'btn btn-default', 'id' => 'address_link']) .
                Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['address/create/' . $model->id], ['class' => 'btn btn-default']) . '</span></div>',
        ])->dropDownList($model->getAddressArray(), [
            'onchange' =>new \yii\web\JsExpression("($('#address_link').attr('href', '/address/update/' + this.value));"),
        ]) ?>

    <?= $form->field($model, 'factaddress', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['address/update/' . (string)$model->factaddress], ['class' => 'btn btn-default', 'id' => 'factaddress_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['address/create/' . $model->id], ['class' => 'btn btn-default']) . '</span></div>',
        ])->dropDownList($model->getAddressArray(), [
            'onchange' =>new \yii\web\JsExpression("($('#factaddress_link').attr('href', '/address/update/' + this.value));")
        ]) ?>

    <?= $form->field($model, 'account', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['account/update/' . (string)$model->account], ['class' => 'btn btn-default', 'id' => 'account_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['account/create/' . $model->id], ['class' => 'btn btn-default']) . '</span></div>',
        ])->dropDownList($model->getAccountArray(), [
            'onchange' =>new \yii\web\JsExpression("($('#account_link').attr('href', '/account/update/' + this.value));")
        ]) ?>

    <?= $form->field($model, 'director', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['employee/update/' . (string)$model->director], ['class' => 'btn btn-default', 'id' => 'director_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['employee/create/' . $model->id], ['class' => 'btn btn-default']) . '</span></div>',
        ])->dropDownList($model->getEntityPersonRoleArray(), [
            'onchange' =>new \yii\web\JsExpression("($('#director_link').attr('href', '/employee/update/' + this.value));")
        ]) ?>

    <?= $form->field($model, 'accountant', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['employee/update/' . (string)$model->accountant], ['class' => 'btn btn-default', 'id' => 'accountant_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['employee/create/' . $model->id], ['class' => 'btn btn-default']) . '</span></div>',
        ])->dropDownList($model->getEntityPersonRoleArray(), [
            'onchange' =>new \yii\web\JsExpression("($('#accountant_link').attr('href', '/employee/update/' + this.value));")
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
