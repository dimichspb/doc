<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Product;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'status',
                'value' => function (Product $model) {
                    return $model->getStatusName();
                },
                'filter' => Product::getStatusArray(),
            ],
            'code',
            'name',
            [
                'attribute' => 'material',
                'value' => function (Product $model) {
                    return $model->getMaterialName();
                }
            ],
            'dia',
            'thread',
            'package',
            //'stock',
            [
                'attribute' => 'price',
                'value' => function (Product $model) {
                     return Yii::$app->formatter->asCurrency($model->getValidPriceValue());
                }
            ],
            /*
            [
                'attribute' => 'image_file',
                'format' => ['image',['width'=>'50', 'alt' => 'Нет изображения']],
                'value' => function (Product $model) {
                    return $model->getImageFilePath();
                }
            ],
            [
                'attribute' => 'drawing_file',
                'format' => ['image',['width'=>'50', 'alt' => 'Нет чертежа']],
                'value' => function (Product $model) {
                    return $model->getDrawingFilePath();
                }
            ],
            */
            ['class' => 'yii\grid\ActionColumn','contentOptions'=>[ 'style'=>'width: 70px'],],
        ],

        'summary' => $dataProvider->count <2 ? "" : "Показано {begin} - {end} из {totalCount} товаров",
        'emptyText' => 'Нет результатов',

    ]); ?>
<?php Pjax::end(); ?></div>
