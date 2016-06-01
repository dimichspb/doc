<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name . ' ' . $model->getFullCode();
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs('
    jQuery(document).on("submit", "#product-add-form", function (event) {jQuery.pjax.submit(event, "#shopping-cart", {"push":true,"replace":false,"timeout":1000,"scrollTo":false});});
');
?>
<div class="product-view">
    <div class="row">
        <div class="col-md-6 text-center">
            <?php Pjax::begin([
                'id' => 'search-products-list',
            ]) ?>
            <?php $form = ActiveForm::begin([
                'id' => 'product-add-form',
                'method' => 'POST',
            ]); ?>
            <h3>1. Подберите нужный крепеж</h3>
            <div class="panel panel-default">
                <div class="panel-heading"><?= Html::encode($model->getFullCode()) ?></div>
                <div class="panel-body row">
                    <div class="col-ms-4">
                        <?= Html::a(Html::img($model->getImageFilePath(), ['width' => 150]), ['product/view', 'id' => $model->id]) ?>
                    </div>
                    <div class="col-ms-8">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'code',
                                'name',
                                ['attribute' => 'material', 'value' => $model->getMaterialName()],
                                ['attribute' => 'dia', 'value' => $model->getDia()],
                                ['attribute' => 'thread', 'value' => $model->getThread()],
                                ['attribute' => 'length', 'value' => $model->getLength()],
                                ['attribute' => 'package', 'value' => $model->getPackage()],
                            ],
                        ]) ?>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-xs-4 col-md-7 text-center">
                            Цена: <?= (isset($model->price) && $model->price > 0)? Yii::$app->formatter->format($model->price, 'currency'): 'по запросу'; ?>
                        </div>
                        <div class="col-xs-8 col-md-5 text-right">
                            <div class="input-group">
                                <?= Html::textInput('product-count-' . $model->id, $model->getCount(), ['type' => 'number', 'class' => 'form-control', 'min' => 1]) ?>
                                <span class="input-group-btn">
                                    <?= Html::submitButton('<span class="glyphicon glyphicon-plus-sign"></span> Добавить', ['class' => 'btn btn-success', 'name' => 'add', 'value' => $model->id]); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end() ?>
            <?php Pjax::end() ?>
        </div>
        <div class="col-md-6 text-center">
            <h3>2. Разместите запрос</h3>
            <?= $this->render('/site/_cart', [
                'cart' => $cart,
            ]) ?>
        </div>
    </div>
</div>
