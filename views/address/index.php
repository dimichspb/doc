<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Address;
use app\models\Country;
use app\models\City;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AddressSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Адреса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-index">
    <p>
        <?= Html::a('Добавить адрес', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'country',
                'value' => function (Address $model) {
                    return $model->getCountryName();
                },
                'filter' => Country::getCountryArray(),
            ],
            [
                'attribute' => 'city',
                'value' => function (Address $model) {
                    return $model->getCityName();
                },
                'filter' => City::getCityArray(),
            ],
            'postcode',
            'street',
            'housenumber',
            'building',
            'office',
            // 'comments',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
