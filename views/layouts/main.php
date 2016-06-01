<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */

$hideTitle = in_array(Yii::$app->controller->action->id, ['index', 'confirm']);

$username = Yii::$app->user->isGuest? 'Гость': Yii::$app->user->identity->username;
if (Yii::$app->user->isGuest) {
    $avatar = '/images/guest.jpg';
} else {
    switch (Yii::$app->user->identity->getRoleName()) {
        case User::ROLE_ADMIN:
            $avatar = '/images/admin.jpg';
            break;
        case User::ROLE_CUSTOMER:
            $avatar = '/images/customer.jpg';
            break;
        case User::ROLE_SUPPLIER:
            $avatar = '/images/supplier.jpg';
            break;
        default:
            $avatar = '/images/guest.jpg';
    }
}

if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transitionk skin-green-light sidebar-mini <?= Yii::$app->user->isGuest? 'layout-top-nav': 'sidebar-collapse'; ?>">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            [
                'directoryAsset' => $directoryAsset,
                'username' => $username,
                'avatar' => $avatar,
            ]
        ) ?>

        <?= Yii::$app->user->isGuest? '': $this->render(
            'left.php',
            [
                'directoryAsset' => $directoryAsset,
                'username' => $username,
                'avatar' => $avatar,
            ]
        )
        ?>

        <?= $this->render(
            'content.php',
            [
                'content' => $content,
                'directoryAsset' => $directoryAsset,
                'username' => $username,
                'avatar' => $avatar,
                'hideTitle' => $hideTitle,
            ]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
