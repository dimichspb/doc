<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntityRole */
/* @var $entity \app\models\Entity */

$this->title = 'Добавить должность';
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['/entities']];
$this->params['breadcrumbs'][] = ['label' => $entity->getFull(), 'url' => ['/entity/view', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = ['label' => 'Должности', 'url' => ['roles/' . $entity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-role-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
