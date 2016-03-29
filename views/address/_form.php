<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Country;
use app\models\City;

/* @var $this yii\web\View */
/* @var $model app\models\Address */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::hiddenInput('referrer', Yii::$app->request->referrer) ?>

    <?= Html::hiddenInput('entity', Yii::$app->request->get('id')) ?>

    <?= $form->field($model, 'country')->dropDownList(Country::getCountryArray()) ?>

    <?= $form->field($model, 'city')->dropDownList(City::getCityArray()) ?>

    <?= $form->field($model, 'postcode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'housenumber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'building')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'office')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comments')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
