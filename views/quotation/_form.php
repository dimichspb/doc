<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Quotation;
use app\models\Request;
use app\models\Supplier;
use yii\jui\DatePicker;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Quotation::getStatusArray()) ?>

    <?= $form->field($model, 'expire_at')->widget(DatePicker::classname(), [
        'options' => [
            'class' => 'form-control'
        ],
    ]) ?>

    <?= $form->field($model, 'request')->dropDownList(ArrayHelper::map(Request::getActiveAll(), 'id', 'name')) ?>

    <?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Supplier::getActiveAll(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value' => 'Y']) ?>
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

    <?php ActiveForm::end(); ?>

</div>
