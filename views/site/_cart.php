<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;

$this->registerJs('
    jQuery(document).on("submit", "#product-cart-form", function (event) {jQuery.pjax.submit(event, "#shopping-cart", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');
?>

        <?php Pjax::begin([
            'id' => 'shopping-cart',
        ]) ?>
        <?php $form = ActiveForm::begin([
            'id' => 'product-cart-form',
            'method' => 'POST',
        ]); ?>
        <table class="table table-striped table-hover table-condensed">
            <?php foreach ($cart as $productId => $product) { ?>
                <tr>
                    <td class="col-xs-7"> 
                        <?= $product->getFullname() ?>
                    </td>
                    <td class="col-xs-3">    
                        <?= $product->getQuantity() ?>
                    </td>
                    <td class="col-xs-2">   
                        <?= (isset($hideRemove) && $hideRemove)? '': Html::submitButton('<span class="glyphicon glyphicon-remove"></span>', ['class' => 'btn btn-link btn-xs', 'name' => 'remove', 'value' => $product->id]) ?>
                    </td>
                </tr>    
            <?php } ?>
        </table>
        <?php ActiveForm::end() ?>
        <?php if (isset($hideRemove) && $hideRemove): ?>
        <?php else: ?>
        <?php $form = ActiveForm::begin([
            'id' => 'place-request-form',
            'action' => ['request'],
        ]); ?>
        <?php 
            if (count($cart)) {
                echo Html::submitButton('<span class="glyphicon glyphicon-ok"></span> Получить счет', ['class' => 'btn btn-primary', 'name' => 'request', 'value' => 'Y', 'data-pjax' => 0]);
            }
        ?>
        <?php ActiveForm::end() ?>
        <?php endif ?>
        <?php Pjax::end() ?>