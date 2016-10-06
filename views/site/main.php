<?php

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $searchModel app\models\ProductSearch */
/* @var $cart \app\models\Product[] */
/* @var $codesArray [] */

/*
$this->registerJs('
    jQuery(document).on("change", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("submit", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("input",  "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');
*/
/*
$this->registerJs('
    $("#search-products-form").on("change", function (event) {
        $.pjax.submit(event, "#search-products-list");
        return false;
    });
   
    $("#search-products-form").on("change", function (event) {
        $.pjax.submit(event, "#search-products-list");
        return false;
    });
    
    $("#search-products-form").on("change", function (event) {
        $.pjax.submit(event, "#search-products-list");
        return false;
    });
');
*/
$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Все очень просто!</h1>
            <p class="lead">Крепеж со склада по <strong>лучшим ценам</strong> всего в <strong>два простых шага</strong></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 text-center">
            <h3>Поиск крепежа</h3>
            <?= $this->render('_search', [
                'model' => $searchModel,
            ]) ?>
        </div>
        <div class="col-md-6 text-center">
            <h3>Подбор товара</h3>
            <?= $this->render('_cart', [
                'cart' => $cart,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <hr>
    </div>
    <div class="body-content">
        <?php Pjax::begin([
            'id' => 'search-products-list',
        ]) ?>
        <?php $form = ActiveForm::begin([
            'id' => 'product-add-form',
            'method' => 'POST',
        ]); ?>
        <?= $this->renderAjax('_products', [
            'form' => $form,
            'dataProvider' => $dataProvider,
            'codesArray' => $codesArray,
        ]) ?>
        <?php ActiveForm::end() ?>
        <?php Pjax::end() ?>
    </div>
</div>
