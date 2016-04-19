<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Payment;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить оплату', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function (Payment $payment) {
                    return $payment->getStatusName();
                },
                'filter' => Payment::getStatusArray(),
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
                'attribute' => 'order',
                'format' => 'raw',
                'value' => function (Payment $payment) {
                    return Html::a($payment->getOrderName(), Url::to(['order/' . $payment->order]));
                }
            ],
            [
                'attribute' => 'entity',
                'format' => 'raw',
                'value' => function (Payment $payment) {
                    return Html::a($payment->getEntityName(), Url::to(['entity/' . $payment->entity]));
                }
            ],
            'amount:currency',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
