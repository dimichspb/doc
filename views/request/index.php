<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Request;
use app\models\Customer;
use app\models\Product;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать запрос', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (Request $model) {
                    return $model->getStatusName();
                },
                'filter' => Request::getStatusArray(),
            ],
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'options' => [
                        'class' => 'form-control'
                    ],
                ]),

            ],
            [
                'attribute' => 'customer',
                'value' => function (Request $model) {
                    return $model->getCustomerName();
                },
                'filter' => ArrayHelper::map(Customer::getActiveAll(), 'id', 'name'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}  {update}  {delete}  {quotation}',
                'buttons' => [
                    'quotation' => function ($url, Request $model) {
                        if ($model->status === Request::STATUS_ACTIVE || $model->status === Request::STATUS_QUOTED) {
                            return Html::a('<span class="glyphicon glyphicon-plus"></span>', ['quotation/create/' . $model->id]);
                        }
                    }
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
