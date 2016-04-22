<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'id' => 'search-products-form',
    ]); ?>

        <?= $form->field($model, 'complex_name', [
            'addon' => [
                'groupOptions' => ['class'=>'input-group-lg'],
                'append' => [
                    'content' => Html::submitButton('Go', ['class'=>'btn btn-default']), 
                    'asButton' => true,
                ],
            ],
        ])->textInput(['placeholder'=>'Начните поиск...'])->label(false) ?>

    <?php ActiveForm::end(); ?>

</div>
