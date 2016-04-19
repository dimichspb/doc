<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use kartik\select2\Select2;
use app\models\Order;
use app\models\Shipper;
use app\models\DeliveryToProduct;

/* @var $this yii\web\View */
/* @var $model app\models\Delivery */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider DataProviderInterface */
?>

<div class="delivery-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'order')->dropDownList(ArrayHelper::map(Order::getActiveAndPaidAll(), 'id', 'name'), ['id' => 'deliverz-order-input']) ?>

    <?= $form->field($model, 'shipper')->dropDownList(ArrayHelper::map(Shipper::getActiveAll(), 'id', 'name'), ['id' => 'deliverz-order-input']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value' => 'Y']) ?>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'label' => 'Артикул',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'label' => 'Наименование',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'label' => 'Материал',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'label' => 'Диаметр',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'label' => 'Длина',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return $deliveryToProduct->getProductOne()->thread;
                },
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
                'format' => 'raw',
                'value' => function(DeliveryToProduct $deliveryToProduct) {
                    return Html::input('number', 'quantity[' . $deliveryToProduct->product . ']', isset($deliveryToProduct->quantity)? $deliveryToProduct->quantity: 0);
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
        'data' => ArrayHelper::map($model->getOrderOne()->getProductsAll(), 'id', 'fullname'),
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
