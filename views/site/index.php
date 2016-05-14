<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider DataProviderInterface */
/* @var $searchModel app\models\ProductSearch */
$this->registerJs('
    jQuery(document).on("change", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("submit", "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
    jQuery(document).on("input",  "#search-products-form", function (event) {jQuery.pjax.submit(event, "#search-products-list", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');

$this->title = Yii::$app->params['name'];
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
            <h3>1. Подберите нужный крепеж</h3>
            <?= $this->render('_search', [
                'model' => $searchModel,
            ]) ?>
        </div>
        <div class="col-md-6 text-center">
            <h3>2. Разместите запрос</h3>
            <?= $this->render('_cart', [
                'cart' => $cart,
            ]) ?>
        </div>
    </div>
    <div class="row">
        <hr>
    </div>
    <div class="body-content">
        <?= $this->render('_products', [
            'dataProvider' => $dataProvider,
        ]) ?>
    </div>
</div>
