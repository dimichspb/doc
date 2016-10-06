<?php

/* @var $model \app\models\Product */
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-sm-6 col-md-4">
    <div class="panel panel-default"> 
        <div class="panel-heading"><?= Html::encode($model->getFullCode()) ?></div>
        <div class="panel-body panel-fixed-height row">
                <div class="col-xs-5 text-center">
                    <?= Html::a(Html::img($model->getImageFilePath()), ['product/view', 'id' => $model->id]) ?>
                </div>
                <div class="col-xs-7">
                    <strong><?= Html::encode($model->name); ?></strong><br>
                    <small><?= Html::encode($model->getMaterialName()); ?></small>
                    <br><br>
                    <?= (isset($model->price) && $model->price > 0)? Yii::$app->formatter->format($model->price, 'currency') . ' / шт.': 'Цена по запросу'; ?>
                </div>
        </div>
        <div class="panel-footer">
            <div class="row">
            <div class="col-xs-5 text-left">
                <?php // echo Html::a('<span class="glyphicon glyphicon-info-sign"></span> Подробнее', ['product/view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 0]) ?>
            </div>
            <div class="col-xs-7 text-right">
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
</div>