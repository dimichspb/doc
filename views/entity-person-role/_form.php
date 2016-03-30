<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */
/* @var $form yii\widgets\ActiveForm */
/* @var $entity \app\models\Entity */
?>

<div class="entity-person-role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::hiddenInput('referrer', Yii::$app->request->referrer) ?>

    <?= $form->field($model, 'role', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['role/update/' . (string)$model->role], ['class' => 'btn btn-default', 'id' => 'role_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['role/create/' . (string)$model->entity], ['class' => 'btn btn-default']) . '</span></div>',
    ])->dropDownList($entity->getRoleArray(), [
        'onchange' =>new \yii\web\JsExpression("($('#role_link').attr('href', '/role/update/' + this.value));")
    ]) ?>

    <?= $form->field($model, 'person', [
        'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn">'.
            Html::a('<span class="glyphicon glyphicon glyphicon-pencil"></span>', ['person/update/' . (string)$model->person], ['class' => 'btn btn-default', 'id' => 'person_link']) .
            Html::a('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['person/create/' . (string)$model->entity], ['class' => 'btn btn-default']) . '</span></div>',
    ])->dropDownList($entity->getPersonArray(), [
        'onchange' =>new \yii\web\JsExpression("($('#person_link').attr('href', '/person/update/' + this.value));")
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
