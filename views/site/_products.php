<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $dataProvider DataProviderInterface */

$this->registerJs('
    jQuery(document).on("submit", "#product-add-form", function (event) {jQuery.pjax.submit(event, "#shopping-cart", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');

?>
    <?php Pjax::begin([
        'id' => 'search-products-list',
    ]) ?>
    <?php $form = ActiveForm::begin([
        'id' => 'product-add-form',
        'method' => 'POST',
    ]); ?>
    <?= Nav::widget([
        'items' => [
            [
                'label' => 'DIN 1',
                'url' => ['site/main', 'codeArray[]' => '1'],
                'active' => true,
            ],
            [
                'label' => 'DIN 2',
                'url' => ['site/main', 'codeArray[]' => '2'],
                'active' => true,
            ],
            [
                'label' => 'DIN 3',
                'url' => ['site/main', 'codeArray[]' => '3'],
            ],
        ],
        'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
    ]);
    ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'div',
            'id' => 'products-list-view',
        ],
        'layout' => '<div class="row">{items}</div><div class="row"><div class="col-xs-12 text-center">{pager}</div></div>',
        'itemView' => function ($model, $key, $index, $widget) use ($form) {
            return $this->render('_product',['form' => $form, 'model' => $model]);

            // or just do some echo
            // return $model->title . ' posted by ' . $model->author;
        },
        'itemOptions' => [
            'tag' => false,
        ],
        'pager' => [
            'firstPageLabel' => '<span class="glyphicon glyphicon-fast-backward"></span>',
            'lastPageLabel'  => '<span class="glyphicon glyphicon-fast-forward"></span>',
            'nextPageLabel'  => '<span class="glyphicon glyphicon-step-forward"></span>',
            'prevPageLabel'  => '<span class="glyphicon glyphicon-step-backward"></span>',
            'maxButtonCount' => 3,
        ],
    ]) ?>
    <?php ActiveForm::end() ?>
    <?php Pjax::end() ?>
                
