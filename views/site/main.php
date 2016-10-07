<?php

use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\DataProviderInterface */
/* @var $searchModel app\models\ProductSearch */
/* @var $cart \app\models\Product[] */
/* @var $codesArray [] */


\yii\widgets\PjaxAsset::register($this);
$this->title = Yii::$app->name;
?>
<div class="site-index">
    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Все очень просто!</h1>
            <p class="lead">Крепеж со склада по <strong>лучшим ценам</strong> всего в <strong>два простых шага</strong></p>
        </div>
    </div>
    <?php Pjax::begin(['id' => 'main-pjax']) ?>
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
        <?= $this->renderAjax('_products', [
            'dataProvider' => $dataProvider,
            'codesArray' => $codesArray,
        ]) ?>
    </div>
    <?php Pjax::end() ?>
</div>
