<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Price;
use app\models\Product;
use app\models\Supplier;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PriceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цены';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить цену', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'status',
                'value' => function (Price $model) {
                    return $model->getStatusName();
                },
                'filter' => Price::getStatusArray(),
            ],
            //'created_at',
            //'updated_at',
            [
                'attribute' => 'started_at',
                'value' => 'started_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'started_at',
                        'options' => [
                            'class' => 'form-control'
                        ],
                    ]),

            ],
            [
                'attribute' => 'expire_at',
                'value' => 'expire_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'expire_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                ]),

            ],
            [
                'attribute' => 'product',
                'value' => function (Price $model) {
                    return $model->getProductFullname();
                },
                'filter' => ArrayHelper::map(Product::getActiveAll(), 'id', 'fullname'),
            ],
            [
                'attribute' => 'supplier',
                'value' => function (Price $model) {
                    return $model->getSupplierName();
                },
                'filter' => ArrayHelper::map(Supplier::find()->all(), 'id', 'name'),
            ],
            [
                'attribute' => 'quantity',
                'value' => function (Price $model) {
                    return $model->quantity == 0? 'Любое': $model->quantity;
                },
            ],
            'value:currency',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
