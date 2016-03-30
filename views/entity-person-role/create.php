<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntityPersonRole */
/* @var $entity \app\models\Entity */

$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники ' . $entity->getFull(), 'url' => ['employees/' . $entity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-person-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'entity' => $entity,
    ]) ?>

</div>
