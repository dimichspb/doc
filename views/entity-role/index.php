<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EntityRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $entity \app\models\Entity */

$this->title = 'Должности';
$this->params['breadcrumbs'][] = ['label' => 'Юридические лица', 'url' => ['/entities']];
$this->params['breadcrumbs'][] = ['label' => $entity->getFull(), 'url' => ['/entity/view', 'id' => $entity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-role-index">
    <p>
        <?= Html::a('Добавить должность', ['role/create/' . $entity->id], ['class' => 'btn btn-success']) ?>
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
