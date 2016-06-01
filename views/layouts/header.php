<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $username string */
/* @var $avatar string */

?>

<header class="main-header">
    <?php if(!Yii::$app->user->isGuest): ?>
        <div class="hidden-xs hidden-sm">
            <?= Html::a('<span class="logo-mini"><img src="/favicon.ico" alt="' . Yii::$app->name . '" title="' . Yii::$app->name . '"></span><span class="logo-lg"><img src="/favicon.ico" alt="' . Yii::$app->name . '" title="' . Yii::$app->name . '">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
        </div>
    <?php endif; ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <?php if(!Yii::$app->user->isGuest): ?>
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"><span class="sr-only">Меню</span></a>
        <?php endif ?>
        <?= Html::a('<span class="logo-lg"><img src="/favicon.ico" alt="' . Yii::$app->name . '" title="' . Yii::$app->name . '">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'navbar-brand visible-xs visible-sm']) ?>
        <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <?php if(Yii::$app->user->isGuest): ?>
                    <li class="dropdown user user-menu">
                        <?= Html::a('
                            <img src="'. $avatar .'" class="user-image" alt="Войти"/>
                            <span class="hidden-xs">'. $username .'</span>', ['site/login']) ?>
                    </li>
                    <?php else: ?>
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= $avatar ?>" class="user-image" alt="<?= $username ?>"/>
                            <span class="hidden-xs"><?= $username ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?= $avatar ?>" class="img-circle"
                                     alt="<?= $username ?>"/>

                                <p>
                                    <?= $username ?>
                                    <small><?= Yii::$app->user->isGuest? '': Yii::$app->user->identity->getRoleDescription(); ?></small>
                                </p>
                            </li>
                            <?php if (!Yii::$app->user->isGuest): ?>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <?= Html::a(
                                            'Профиль',
                                            Url::to(['/user/view', 'id' => Yii::$app->user->identity->getId()]),
                                            ['class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                    <div class="pull-right">
                                        <?= Html::a(
                                            'Выйти',
                                            ['/site/logout'],
                                            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                        ) ?>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
    </nav>
</header>
