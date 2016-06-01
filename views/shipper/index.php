<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ShipperSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перевозчики';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shipper-index">
    <p>
        <?= Html::a('Добавить перевозчика', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
