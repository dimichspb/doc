<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\EntityPersonRole;

/* @var $this yii\web\View */
/* @var $searchModel app\models\EntityPersonRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $entity \app\models\Entity */

$this->title = 'Сотрудники ' . $entity->getFull();
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="entity-person-role-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить сотрудника', ['employee/create/' . $entity->id], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'role',
                'value' => function (EntityPersonRole $model) {
                    return $model->getRoleName();
                },
            ],
            [
                'attribute' => 'person',
                'value' => function (EntityPersonRole $model) {
                    return $model->getPersonFullname();
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
