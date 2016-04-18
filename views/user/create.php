<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $customersDataProvider DataProviderInterface */
/* @var $suppliersDataProvider DataProviderInterface */
/* @var $allowEdit boolean */

$this->title = 'Добавить пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'customersDataProvider' => $customersDataProvider,
        'suppliersDataProvider' => $suppliersDataProvider,
        'allowEdit' => $allowEdit,
    ]) ?>

</div>
