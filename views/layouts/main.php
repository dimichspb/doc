<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Alert;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;
use app\commands\RbacController;

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

    $menuItems = Yii::$app->topMenu->asArray();    

    if (Yii::$app->user->isGuest) {
        $menuItems[] =
            ['label' => '<span class="glyphicon glyphicon-log-in"></span> Войти', 'url' => ['/site/login']];
    } else {
        $menuItems[] =
            ['label' => '<span class="glyphicon glyphicon-user"></span> ' . Yii::$app->user->identity->username, 'url' => ['/user/' . Yii::$app->user->identity->getId()]];

        $menuItems[] =
            [
                'label' => '<span class="glyphicon glyphicon-log-out"></span> Выйти',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post'],
            ];
    }
    //echo "<br><br><br>";
    //var_dump($menuItems);
    //die();


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) { ?>
        <?= Alert::widget([
            'options' => [
                'class' => 'alert-' . $type,
            ],
            'body' => $message,
        ]); ?>
        <?php } ?>
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
