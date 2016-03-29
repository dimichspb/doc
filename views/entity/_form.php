<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
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

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Entity::getStatusArray()) ?>

    <?= $form->field($model, 'entity_form')->dropDownList(EntityForm::getEntityFormsArray()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-xs-12 col-md-8">
    <?= $form->field($model, 'address')->dropDownList($model->getAddressArray()) ?>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Изменить адрес', ['address/update'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
            <?= Html::a('Добавить адрес', ['address/create'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
    <?= $form->field($model, 'factaddress')->dropDownList($model->getAddressArray()) ?>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Изменить адрес', ['address/update'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Добавить адрес', ['address/create'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
    <?= $form->field($model, 'account')->dropDownList($model->getAccountArray()) ?>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Изменить счет', ['account/update'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Добавить счет', ['account/create'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
    <?= $form->field($model, 'director')->dropDownList($model->getEntityPersonRoleArray()) ?>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Изменить сотрудника', ['entityrole/update'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Добавить сотрудника', ['entityrole/create'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-8">
    <?= $form->field($model, 'accountant')->dropDownList($model->getEntityPersonRoleArray()) ?>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Изменить сотрудника', ['entityrole/update'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="form-group">
                <label class="control-label">&nbsp;</label>
                <?= Html::a('Добавить сотрудника', ['entityrole/create'], ['class' => 'btn btn-default btn-block']) ?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
