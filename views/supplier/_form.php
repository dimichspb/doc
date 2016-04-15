<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use app\models\Supplier;
use app\models\Entity;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Supplier */
/* @var $form yii\widgets\ActiveForm */
/* @var $dataProvider \yii\data\DataProviderInterface */

?>

<div class="supplier-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'status')->dropDownList(Supplier::getStatusArray()) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value'=>'Y']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'full',
            'inn',
            'addressFull',
            [
                'class' => '\yii\grid\ActionColumn',
                'template' => '{remove}',
                'buttons' => [
                    'remove' => function ($url, Entity $model) {
                        return Html::submitButton('<span class="glyphicon glyphicon-remove"></span>', [
                            'class' => 'btn btn-link',
                            'name' => 'remove',
                            'value' => $model->id,
                        ]);
                    }
                ],
            ],
        ],
    ]) ?>

    <?= Select2::widget([
        'name' => 'addEntity',
        'data' => ArrayHelper::map(Entity::getActiveAll(), 'id', 'full'),
        'options' => ['placeholder' => 'Добавить юр. лицо ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'addon' => [
            'append' => [
                'content' => Html::submitButton('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['class' => 'btn btn-block btn-default', 'name' => 'add', 'value' => 'Y']),
                'asButton' => true,
            ],
        ],
    ]) ?>

    <?php ActiveForm::end(); ?>

</div>
