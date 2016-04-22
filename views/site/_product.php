<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="col-sm-6 col-md-4">
    <div class="panel panel-default"> 
        <div class="panel-heading"><?= Html::encode($model->code) ?></div>
        <div class="panel-body panel-fixed-height row">
                <div class="col-xs-5">
                    <?= Html::a(Html::img($model->getImageFilePath(), ['width' => 100]), ['product/view', 'id' => $model->id]) ?>
                </div>
                <div class="col-xs-7">
                    <?= Html::encode($model->name); ?>
                    <hr>
                    <?= Yii::$app->formatter->format($model->price, 'currency'); ?>
                </div>
        </div>
        <div class="panel-footer">
            <div class="row">
            <div class="col-xs-5 text-left">
                <?= Html::a('<span class="glyphicon glyphicon-info-sign"></span> Подробнее', ['product/view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 0]) ?>
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