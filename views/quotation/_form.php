<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Quotation;
use app\models\Request;
use app\models\Supplier;
use app\models\QuotationToProduct;
use yii\jui\DatePicker;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider \yii\data\DataProviderInterface */
?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'request')->dropDownList(ArrayHelper::map(Request::getActiveAndQuotedAll(), 'id', 'name'), ['id' => 'quotation-request-input']) ?>

    <?= $form->field($model, 'supplier')->dropDownList(ArrayHelper::map(Supplier::getActiveAll(), 'id', 'name')) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value' => 'Y']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'label' => 'Артикул',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'label' => 'Наименование',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'label' => 'Материал',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'label' => 'Диаметр',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'label' => 'Длина',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->thread;
                },
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
                'format' => 'raw',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return Html::input('number', 'quantity[' . $quotationToProduct->product . ']', isset($quotationToProduct->quantity)? $quotationToProduct->quantity: 0);
                },
            ],
            [
                'attribute' => 'price',
                'label' => 'Цена',
                'format' => 'raw',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return Html::input('number', 'price[' . $quotationToProduct->product . ']', isset($quotationToProduct->price)? $quotationToProduct->price: "0.00", ['step' => '0.01', 'min' => '0.00']);
                }
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
        'data' => ArrayHelper::map($model->getRequestOne()->getProductsAll(), 'id', 'fullname'),
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
