<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Price */

$this->title = 'Добавить цену';
$this->params['breadcrumbs'][] = ['label' => 'Цены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
