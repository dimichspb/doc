<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

$this->title = 'Подтверждение запроса';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin([
    'id' => 'confirm-request-form',
]) ?>
<div class="site-request">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1><?= $this->title ?></h1>
            <p class="lead">Пожалуйста, заполните следующие поля, чтобы мы могли подготовить расчет</p>
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
        <div class="col-md-6 text-center">
            <?php if (isset($entity->name)): ?>
                <dl class="dl-horizontal">
                    <dt>Организация:</dt>
                    <dd><?= $entity->full ?></dd>
                </dl>
            <?php else: ?>
                <p>Организация не найдена</p>
            <?php endif ?>
        </div>
        <div class="col-md-6 text-center">
            <?php if (isset($user->email)): ?>
                <dl class="dl-horizontal">
                    <dt>Электронная почта:</dt>
                    <dd><?= $user->email ?></dd>
                </dl>
            <?php else: ?>
                <p>Электронная почта не найдена</p>
            <?php endif ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6 col-md-offset-3 text-center">
            <hr>
            <h4>Выбранные товары:</h4>
            <?= $this->render('_cart', [
                'cart' => $cart,
                'hideRemove' => true,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <hr>
            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Получить счет', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => 'Y', 'data-pjax' => 0]) ?>
        </div>
    </div>
<?php ActiveForm::end() ?>
</div>
