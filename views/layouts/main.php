<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
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
    <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<span class="glyphicon glyphicon-compressed"></span> ' . Yii::$app->params['name'],
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-default navbar-fixed-top',
        ],
    ]);

    $menuItems = [];

    switch($userRoleName) {

        case RbacController::ADMIN_ROLE_NAME:
            $menuItems[] = [
                'label' => '<span class="glyphicon glyphicon-book"></span> Каталог',
                'items' => [
                    ['label' => '<span class="glyphicon glyphicon-th"></span> Товары', 'url' => ['/product/index']],
                    ['label' => '<span class="glyphicon glyphicon-th-list"></span> Прайслисты', 'url' => ['/price/index']],
                    ['label' => '<span class="glyphicon glyphicon-file"></span> Запросы', 'url' => ['/request/index']],
                    ['label' => '<span class="glyphicon glyphicon-list-alt"></span> Предложения', 'url' => ['/quotation/index']],
                ],
            ];

            $menuItems[] = [
                'label' => '<span class="glyphicon glyphicon-briefcase"></span> Контрагенты',
                'items' => [
                    ['label' => '<span class="glyphicon glyphicon-sunglasses"></span> Клиенты', 'url' => ['/entity/index']],
                    ['label' => '<span class="glyphicon glyphicon-transfer"></span> Поставщики', 'url' => ['/supplier/index']],
                    ['label' => '<span class="glyphicon glyphicon-paperclip"></span> Пользователи', 'url' => ['/user/index']],
                ],
            ];

            $menuItems[] = [
                'label' => '<span class="glyphicon glyphicon-duplicate"></span> Работа',
                'items' => [
                    ['label' => '<span class="glyphicon glyphicon-shopping-cart"></span> Заказы', 'url' => ['/order/index']],
                    ['label' => '<span class="glyphicon glyphicon-piggy-bank"></span> Оплаты', 'url' => ['/payment/index']],
                    ['label' => '<span class="glyphicon glyphicon-plane"></span> Отгрузки', 'url' => ['/delivery/index']],
                    ['label' => '<span class="glyphicon glyphicon-barcode"></span> Склад', 'url' => ['/stock/index']],
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
            ['label' => '<span class="glyphicon glyphicon-log-in"></span> Войти', 'url' => ['/site/login']];
    } else {
        $menuItems[] =
            ['label' => '<span class="glyphicon glyphicon-user"></span> ' . Yii::$app->user->identity->username, 'url' => ['/user/view']];

        $menuItems[] =
            [
                'label' => '<span class="glyphicon glyphicon-log-out"></span> Выйти',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ];
    }



    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Главная', 'url' => ['site/index']],
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
