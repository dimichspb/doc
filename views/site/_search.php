<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs('
    jQuery(document).on("change", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("submit", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});return false});
    //jQuery(document).on("input",  "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');

?>

<div class="product-search">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        <?php $form = ActiveForm::begin([
            'id' => 'search-products-form',
            'method' => 'GET'
        ]); ?>

        <?= $form->field($model, 'complex_name', [
            'addon' => [
                'groupOptions' => ['class'=>'input-group-lg'],
                'append' => [
                    'content' => Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default']), 
                    'asButton' => true,
                ],
            ],
        ])->textInput(['placeholder'=>'Начните поиск...'])->label(false) ?>

        <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
