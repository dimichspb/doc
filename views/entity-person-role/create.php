<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */

$this->title = 'Create Entity Person Role';
$this->params['breadcrumbs'][] = ['label' => 'Entity Person Roles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-person-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
