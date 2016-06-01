<?php

use yii\helpers\Html;
use app\models\Request;
use app\models\RequestToProduct;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $request app\models\Request */

$this->title = 'Запрос: ' . $request->id;
?>
<div class="message-body">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3>
        Появлися новый запрос с сайта <?= Html::a(Yii::$app->params['domain'], Yii::$app->params['domain']) ?>.
    </h3>
    <hr>
    <p>
        Посмотреть запрос подборно можно кликнув по ссылке: <?= Html::a('Запрос ' . $request->id, Yii::$app->params['domain']. '/request/' . $request->id) ?>
    <p>
        Добавить свое коммерческое предложение можно кликнув по ссылке: <?= Html::a('Добавить предложение', Yii::$app->params['domain'] . '/quotation/create/' . $request->id) ?>
    </p>
    <hr>
    <?= DetailView::widget([
        'model' => $request,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => $request->getStatusName(),
            ],
            'created_at:date',
            [
                'attribute' => 'customer',
                'value' => Html::a($request->getCustomerName(), Yii::$app->params['domain'] . '/customer/' . $request->customer),
                'format' => 'raw',
            ],
        ],
    ]) ?>
    <br><br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '',
        'columns' => [
            [
                'attribute' => 'product.code',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->code;
                },
            ],
            [
                'attribute' => 'product.name',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->name;
                },
            ],
            [
                'attribute' => 'product.material',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->getMaterialName();
                },
            ],
            [
                'attribute' => 'product.dia',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->dia;
                },
            ],
            [
                'attribute' => 'product.thread',
                'value' => function(RequestToProduct $requestToProduct) {
                    return $requestToProduct->getProductOne()->thread;
                },
            ],
            'quantity',
        ],
    ]) ?>
</div>
