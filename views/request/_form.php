<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Request;
use app\models\Customer;
use app\models\Product;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\models\RequestToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $tableModel \app\models\RequestToProduct */

?>

<div class="request-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'enableClientValidation' => false,
    ]); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(Request::getStatusArray()) ?>
    
    <?= $form->field($model, 'customer')->dropDownList(ArrayHelper::map(Customer::getActiveAll(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value'=>'Y']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->thread;
                },
            ],
            [
                'attribute' => 'quantity',
                'format' => 'raw',
                'value' => function(RequestToProduct $requestToProduct) {
                    return Html::input('text', 'quantity[' . $requestToProduct->product . ']', isset($requestToProduct->quantity)? $requestToProduct->quantity: 0);
                },
            ],
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{remove}',
                'buttons' => [
                    'remove' => function ($url, $model) {
                        return Html::submitButton('<span class="glyphicon glyphicon-remove"></span>', [
                            'class' => 'btn btn-link',
                            'name' => 'remove',
                            'value' => $model->product,
                        ]);
                    }
                ],
            ],
        ],
    ]) ?>

    <?= Select2::widget([
        'name' => 'addProduct',
        'data' => ArrayHelper::map(Product::getActiveAll(), 'id', 'fullname'),
        'options' => ['placeholder' => 'Добавить товар ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'addon' => [
            'append' => [
                'content' => Html::submitButton('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['class' => 'btn btn-block btn-default', 'name' => 'add', 'value' => 'Y']),
                'asButton' => true,
            ],
        ],
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
