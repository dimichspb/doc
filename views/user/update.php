<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $customersDataProvider DataProviderInterface */
/* @var $suppliersDataProvider DataProviderInterface */
/* @var $allowEdit boolean */

$this->title = 'Изменить пользователя: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="user-update">
    <?= $this->render('_form', [
        'model' => $model,
        'customersDataProvider' => $customersDataProvider,
        'suppliersDataProvider' => $suppliersDataProvider,
        'allowEdit' => $allowEdit,
    ]) ?>
</div>
