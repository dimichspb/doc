<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Request */
/* @var $dataProvider \yii\data\DataProviderInterface */

$this->title = 'Создать запрос';
$this->params['breadcrumbs'][] = ['label' => 'Запросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>
</div>
