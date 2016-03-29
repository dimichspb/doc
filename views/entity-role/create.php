<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntityRole */

$this->title = 'Create Entity Role';
$this->params['breadcrumbs'][] = ['label' => 'Entity Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
