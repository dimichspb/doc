<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\User;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;
use app\models\Supplier;
use app\models\Customer;
use app\commands\RbacController;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $customersDataProvider DataProviderInterface */
/* @var $suppliersDataProvider DataProviderInterface */
/* @var $allowEdit boolean */

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => !$allowEdit]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => !$allowEdit]) ?>

    <?= $form->field($model, 'role')->listBox(User::getAllRoles(), ['multiple'=>'multiple']) ?>

    <?= $form->field($model, 'status')->dropDownList(User::getStatusArray()) ?>
    
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'name' => 'save', 'value' => 'Y']) ?>
    </div>
    <?php 
    
    $customersGridView = GridView::widget([
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
    
    $customersAddSelect = Select2::widget([
        'name' => 'customer',
        'data' => ArrayHelper::map(Customer::getActiveAll(), 'id', 'name'),
        'options' => ['placeholder' => 'Добавить клиента ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'addon' => [
            'append' => [
                'content' => Html::submitButton('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['class' => 'btn btn-block btn-default', 'name' => 'addCustomer', 'value' => 'Y']),
                'asButton' => true,
            ],
        ],
    ]);
    
    $suppliersGridView = GridView::widget([
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
    
    $supplierAddSelect = Select2::widget([
        'name' => 'supplier',
        'data' => ArrayHelper::map(Supplier::getActiveAll(), 'id', 'name'),
        'options' => ['placeholder' => 'Добавить поставщика ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'addon' => [
            'append' => [
                'content' => Html::submitButton('<span class="glyphicon glyphicon glyphicon-plus"></span>', ['class' => 'btn btn-block btn-default', 'name' => 'addSupplier', 'value' => 'Y']),
                'asButton' => true,
            ],
        ],
    ]);
    
    ?>
    <?= Tabs::widget([
        'items' => [
            [
                'label' => 'Связанные клиенты',
                'content' => $customersGridView . $customersAddSelect,
                'visible' => array_key_exists(RbacController::CUSTOMER_ROLE_NAME, Yii::$app->authManager->getRolesByUser($model->id)),
            ],
            [
                'label' => 'Связанные поставщики',
                'content' => $suppliersGridView . $supplierAddSelect,
                'visible' => array_key_exists(RbacController::SUPPLIER_ROLE_NAME, Yii::$app->authManager->getRolesByUser($model->id)),
            ],
        ],
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
