<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Delivery */
/* @var $dataProvider DataProviderInterface */

$this->title = 'Добавить Отгрузку';
$this->params['breadcrumbs'][] = ['label' => 'Отгрузки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-create">
    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>
</div>
