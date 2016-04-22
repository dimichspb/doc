<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Подтверждение запроса';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    jQuery(document).on("submit", "#confirm-request-form", function (event) {jQuery.pjax.submit(event, "#request-form", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("change", "#confirm-request-form", function (event) {jQuery.pjax.submit(event, "#request-form", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');
?>
<div class="site-request">
    <?php Pjax::begin([
        'id' => 'request-form',
    ]) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'confirm-request-form',
        'method' => 'POST',
    ]) ?>
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1><?= $this->title?></h1>
            <p class="lead"Пожалуйста, заполните следующие поля, чтобы мы могли подготовить расчет</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            <h3>1. Пожалуйста, введите ИНН организации</h3>
            <div class="input-group">
                <span class="input-group-addon">ИНН</span>
                <?= Html::textInput('request-inn', '', ['class' => 'form-control', 'placeholder' => '1234567890']); ?>
                <span class="input-group-btn">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default', 'name' => 'inn-button', 'value' => 'Y']) ?>
                </span>
            </div>
        </div> 
        <div class="col-md-6 text-center">
            <h3>2. Пожалуйста, введите e-mail адрес</h3>
            <div class="input-group">
                <span class="input-group-addon">E-mail</span>
                <?= Html::textInput('request-email', '', ['class'=>'form-control', 'placeholder' => 'user@domain.com']); ?>
                <span class="input-group-btn">
                    <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default', 'name' => 'email-button', 'value' => 'Y' ]) ?>
                </span>
            </div>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-12 text-center">
            <hr>
            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Получить счет', ['class' => 'btn btn-primary', 'name' => 'request', 'value' => 'Y', 'data-pjax' => 0]) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
    <?php Pjax::end() ?>
</div>
