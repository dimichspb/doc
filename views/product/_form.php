<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
$materials = \yii\helpers\ArrayHelper::map(\app\models\Material::find()->orderBy('name')->all(), 'id', 'name');
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'status')->dropDownList(Product::getStatusArray()) ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'material')->dropDownList($materials, ['prompt' => 'Выберите материал'])->label('Материал')  ?>

    <?= $form->field($model, 'dia')->textInput() ?>

    <?= $form->field($model, 'thread')->textInput() ?>

    <?= $form->field($model, 'package')->textInput() ?>
    
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'drawingFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
