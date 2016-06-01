<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Entity;
use app\models\EntityForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Юридические лица';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-index">
    <p>
        <?= Html::a('Добавить юр.лицо', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'status',
            //'created_by',
            [
                'attribute' => 'entity_form',
                'value' => function (Entity $model) {
                    return $model->getEntityFormName();
                },
                'filter' => EntityForm::getEntityFormsArray(),
            ],
            'name',
            // 'fullname',
            //'ogrn',
            'inn',
            // 'kpp',
            // 'address',
            // 'factaddress',
            // 'account',
            // 'director',
            // 'accountant',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
