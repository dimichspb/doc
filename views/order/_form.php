<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Quotation;
use app\models\Order;
use app\models\OrderToProduct;
use app\models\Entity;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider DataProviderInterface */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'seller')->dropDownList(ArrayHelper::map(Entity::getActiveAll(), 'id', 'full')) ?>

    <?= $form->field($model, 'quotation')->dropDownList(ArrayHelper::map(Quotation::getActiveAndOrderedAll(), 'id', 'name'), ['id' => 'order-quotation-input']) ?>

    <?= $form->field($model, 'status')->dropDownList(Order::getStatusArray()) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value' => 'Y']) ?>
    </div>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'product.code',
                'label' => 'Артикул',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'label' => 'Наименование',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'label' => 'Материал',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'label' => 'Диаметр',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'label' => 'Длина',
                'value' => function(OrderToProduct $orderToProduct) {
                    return $orderToProduct->getProductOne()->thread;
                },
            ],
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
                'format' => 'raw',
                'value' => function(OrderToProduct $orderToProduct) {
                    return Html::input('number', 'quantity[' . $orderToProduct->product . ']', isset($orderToProduct->quantity)? $orderToProduct->quantity: 0);
                },
            ],
            [
                'attribute' => 'price',
                'label' => 'Цена',
                'format' => 'raw',
                'value' => function(OrderToProduct $orderToProduct) {
                    return Html::input('number', 'price[' . $orderToProduct->product . ']', isset($orderToProduct->price)? $orderToProduct->price: "0.00", ['step' => '0.01', 'min' => '0.00']);
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
        'data' => ArrayHelper::map($model->getQuotationOne()->getProductsAll(), 'id', 'fullname'),
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
