<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\bootstrap\Tabs;
use app\models\Supplier;
use app\models\Customer;
use app\commands\RbacController;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $customersDataProvider DataProviderInterface */
/* @var $suppliersDataProvider DataProviderInterface */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username',
            'email:email',
            [
                'attribute' => 'role',
                'value' => implode(', ', array_keys($model->getRoles())),
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatus(),
            ],
        ],
    ]) ?>
    <?php $customersGridView = 
        GridView::widget([
            'dataProvider' => $customersDataProvider,
            'columns' => [
                'name',
                [
                    'attribute' => 'status',
                    'value' => function (Customer $customer) {
                        return $customer->getStatusName();
                    },
                ]
            ],
            'summary' => '',    
        ]);
    ?>
    <?php $suppliersGridView = 
        GridView::widget([
            'dataProvider' => $suppliersDataProvider,
            'columns' => [
                'name',
                [
                    'attribute' => 'status',
                    'value' => function (Supplier $supplier) {
                        return $supplier->getStatusName();
                    },
                ]
            ],
            'summary' => '', 
        ]);
    ?>
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Связанные клиенты',
                'content' => $customersGridView,
                'visible' => array_key_exists(RbacController::CUSTOMER_ROLE_NAME, Yii::$app->authManager->getRolesByUser($model->id)),
            ],
            [
                'label' => 'Связанные поставщики',
                'content' => $suppliersGridView,
                'visible' => array_key_exists(RbacController::SUPPLIER_ROLE_NAME, Yii::$app->authManager->getRolesByUser($model->id)),
            ],
        ],
    ]); ?>

</div>
