<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this \yii\web\View */
/* @var $content string */

$hideTitle = in_array(Yii::$app->controller->action->id, ['main', 'confirm']);

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

    //if (class_exists('backend\assets\AppAsset')) {
    //    backend\assets\AppAsset::register($this);
    //} else {
        app\assets\AppAsset::register($this);
    //}

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
<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter37980995 = new Ya.Metrika({ id:37980995, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/37980995" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
