<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Entity */

$this->title = 'Изменить юр.лицо: ' . $model->full;
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->full, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="entity-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
