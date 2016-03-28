<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Quotation;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QuotationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Предложения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="quotation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать предложение', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'status',
                'value' => function (Quotation $model) {
                    return $model->getStatusName();
                },
            ],
            'created_at:date',
            //'updated_at',
            'expire_at:date',
            [
                'attribute' => 'request',
                'value' => function (Quotation $model) {
                    return $model->getRequestName();
                }
            ],
            [
                'attribute' => 'supplier',
                'value' => function (Quotation $model) {
                    return $model->getSupplierName();
                },
            ],
            'value:currency',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
