<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->name . ' ' . $model->getFullCode();
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">
    <p>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить этот товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'status',
                'value' => $model->getStatusName(),
            ],
            'code',
            'name',
            ['attribute' => 'material', 'value' => $model->getMaterialName()],
            ['attribute' => 'dia', 'value' => $model->getDia()],
            ['attribute' => 'thread', 'value' => $model->getThread()],
            ['attribute' => 'length', 'value' => $model->getLength()],
            ['attribute' => 'package', 'value' => $model->getPackage()],
            [
                'attribute' => 'price',
                'value' => $model->getValidPriceValue(),
                'format' => 'currency',
            ],
            [
                'attribute' => 'image_file',
                'format' => ['image',['width'=>'100', 'alt' => 'Нет изображения']],
                'value' => $model->getImageFilePath(),
            ],
            [
                'attribute' => 'drawing_file',
                'format' => ['image',['width'=>'100', 'alt' => 'Нет чертежа']],
                'value' => $model->getDrawingFilePath(),
            ],
        ],
    ]) ?>

</div>
