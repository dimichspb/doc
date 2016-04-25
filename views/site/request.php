<?php

/* @var $this yii\web\View */
/* @var $model app\models\RequestForm */
/* @var $entity app\models\Entity */

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Подтверждение запроса';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    jQuery(document).on("submit", "#confirm-request-form", function (event) {jQuery.pjax.submit(event, "#request-form", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');
//    jQuery(document).on("change", "#confirm-request-form", function (event) {jQuery.pjax.submit(event, "#request-form", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});

?>
<div class="site-request">
    <?php $form = ActiveForm::begin([
        'id' => 'confirm-request-form',
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
        'method' => 'POST',
    ]) ?>
    <?php Pjax::begin([
        'id' => 'request-form',
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
            <?= $form->field($model, 'inn', [
                'addon' => [
                    'prepend' => ['content'=>'ИНН'],
                    'append' => [
                        'content' => Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default', 'name' => 'inn-search', 'value' => 'Y']), 
                        'asButton' => true
                    ],
                ],
            ])->textInput(['placeholder' => '1234567890']); ?>
            <?= $entity? serialize($entity): '' ?>
        </div> 
        <div class="col-md-6 text-center">
            <h3>2. Пожалуйста, введите e-mail адрес</h3>
            <?= $form->field($model, 'email', [
                'addon' => [
                    'prepend' => ['content'=>'E-mail'],
                    'append' => [
                        'content' => Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default', 'name' => 'email-search', 'value' => 'Y']), 
                        'asButton' => true
                    ],
                ],
            ])->textInput(['placeholder' => 'user@domain.com']); ?>
        </div>
    </div>  
    <div class="row">
        <div class="col-md-12 text-center">
            <hr>
            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Получить счет', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => 'Y', 'data-pjax' => 0]) ?>
        </div>
    </div>
    <?php Pjax::end() ?>
    <?php ActiveForm::end() ?>
</div>
