<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\Models\User;
use app\commands\RbacController;

AppAsset::register($this);
$userRoleName = User::getUserRoleName();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->params['name'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    $menuItems = [];

    switch($userRoleName) {

        case RbacController::ADMIN_ROLE_NAME:
            $menuItems[] = [
                'label' => 'Каталог',
                'items' => [
                    ['label' => 'Товары', 'url' => ['/product/index']],
                    ['label' => 'Прайслисты', 'url' => ['/price/index']],
                    ['label' => 'Предложения', 'url' => ['/quotation/index']],
                ],
            ];

            $menuItems[] = [
                'label' => 'Контрагенты',
                'items' => [
                    ['label' => 'Клиенты', 'url' => ['/entity/index']],
                    ['label' => 'Поставщики', 'url' => ['/supplier/index']],
                    ['label' => 'Пользователи', 'url' => ['/user/index']],
                ],
            ];

            $menuItems[] = [
                'label' => 'Работа',
                'items' => [
                    ['label' => 'Заказы', 'url' => ['/order/index']],
                    ['label' => 'Оплаты', 'url' => ['/payment/index']],
                    ['label' => 'Отгрузки', 'url' => ['/delivery/index']],
                    ['label' => 'Склад', 'url' => ['/stock/index']],
                ],
            ];

            break;

        case RbacController::SUPPLIER_ROLE_NAME:
            $menuItems[] =
                ['label' => 'Supplier', 'url' => ['/site/login']];
            break;

        case RbacController::CUSTOMER_ROLE_NAME:
            $menuItems[] =
                ['label' => 'Customer', 'url' => ['/site/login']];
            break;
        default:
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] =
            ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] =
            [
                'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ];
    }



    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Yii::$app->params['name'];?> <?= date('Y') ?></p>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
