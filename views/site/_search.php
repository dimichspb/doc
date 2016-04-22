<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="product-search">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        <?php $form = ActiveForm::begin([
            'id' => 'search-products-form',
        ]); ?>

        <?= $form->field($model, 'complex_name', [
            'addon' => [
                'groupOptions' => ['class'=>'input-group-lg'],
                'append' => [
                    'content' => Html::submitButton('<span class="glyphicon glyphicon-search"></span>', ['class'=>'btn btn-default']), 
                    'asButton' => true,
                ],
            ],
        ])->textInput(['placeholder'=>'Start search...'])->label(false) ?>

        <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
