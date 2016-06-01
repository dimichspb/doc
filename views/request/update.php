<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Изменить запрос: ' . $model->getName();
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="request-update">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
