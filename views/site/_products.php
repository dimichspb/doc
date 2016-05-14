<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;

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
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'tag' => 'div',
                'class' => 'row',
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
        ]) ?>
        <?php ActiveForm::end() ?>
        <?php Pjax::end() ?>
                
