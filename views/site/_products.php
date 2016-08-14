<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use kartik\form\ActiveForm;
use yii\widgets\Pjax;
use yii\bootstrap\Nav;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $codesArray [] */

$tabs = [];

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
<?php

foreach ($codesArray as $code) {

    $newDataProvider = clone $dataProvider;
    $newDataProvider->query->andFilterWhere(['code' => $code['code']]);

    var_dump($newDataProvider->query->createCommand()->rawSql);

    $tabs[] = [
        'label' => $code['code'] . ' (' . $code['count'] . ')',
        'content' => ListView::widget([
            'dataProvider' => $newDataProvider,
            'options' => [
                'tag' => 'div',
                'id' => 'products-list-view',
            ],
            'layout' => '<div class="row">{items}</div><div class="row"><div class="col-xs-12 text-center">{pager}</div></div>',
            'itemView' => function ($model, $key, $index, $widget) use ($form) {
                return $this->render('_product',['form' => $form, 'model' => $model]);
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
        ]),
        'active' => $code === $codesArray[0],
    ];
}

?>
    <?= Tabs::widget([
        'items' => $tabs,
    ]) ?>
    <?php ActiveForm::end() ?>
    <?php Pjax::end() ?>
                
