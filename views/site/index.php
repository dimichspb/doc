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

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6 col-md-offset-2 col-lg-offset-3">
                <?= $this->render('_search', [
                    'model' => $searchModel,
                ]) ?>
            </div>
        </div>
        
    </div>

    <div class="body-content">

        <?php Pjax::begin([
            'id' => 'search-products-list',
        ]) ?>
        <?= $this->render('_products', [
            'dataProvider' => $dataProvider,
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>
