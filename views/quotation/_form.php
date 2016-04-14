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
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Quotation */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider \yii\data\DataProviderInterface */
$this->registerJs('
    var url = "'.Url::toRoute('/quotation/create/').'";
    $("#quotation-request-input").on("change", function () {
        window.location.href = url + "/" + this.value;
    });
');

?>

<div class="quotation-form">

    <?php $form = ActiveForm::begin(); ?>

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
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return $quotationToProduct->getProductOne()->thread;
                },
            ],
            [
                'attribute' => 'quantity',
                'format' => 'raw',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return Html::input('number', 'quantity[' . $quotationToProduct->product . ']', isset($quotationToProduct->quantity)? $quotationToProduct->quantity: 0);
                },
            ],
            [
                'attribute' => 'price',
                'format' => 'raw',
                'value' => function(QuotationToProduct $quotationToProduct) {
                    return Html::input('number', 'price[' . $quotationToProduct->product . ']', isset($quotationToProduct->price)? $quotationToProduct->price: "0.00", ['step' => '0.01']);
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

    <?php ActiveForm::end(); ?>

</div>
