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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'status',
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
            'stock',
            [
                'attribute' => 'price',
                'value' => function (Product $model) {
                     return Yii::$app->formatter->asCurrency($model->price);
                }
            ],
            ['class' => 'yii\grid\ActionColumn','contentOptions'=>[ 'style'=>'width: 70px'],],
        ],

        'summary' => $dataProvider->count <2 ? "" : "Показано {begin} - {end} из {totalCount} товаров",
        'emptyText' => 'Нет результатов',

    ]); ?>
<?php Pjax::end(); ?></div>
