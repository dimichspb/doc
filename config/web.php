<?php

use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;

$params = require(__DIR__ . '/params.php');
$configLocal = file_exists(__DIR__ . '/web-local.php')? include(__DIR__ . '/web-local.php'): [];

$config = [
    'id' => 'basic',
    'name' => 'Склад болтов',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2C94dkLUFB4p9_zv5qmVBPgzVje6w9en',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            //'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/' => 'site/main',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'confirm' => 'site/confirm',

                'products' => 'product/index',
                'product/<id:\d+>' => 'product/view',
                'prices' => 'price/index',
                'price/<id:\d+>' => 'price/view',
                'stock'      => 'stock/index',

                'customers' => 'customer/index',
                'customer/<id:\d+>' => 'customer/view',
                'suppliers' => 'supplier/index',
                'supplier/<id:\d+>' => 'supplier/view',
                'entities' => 'entity/index',
                'entity/<id:\d+>' => 'entity/view',
                'users' => 'user/index',
                'user/<id:\d+>' => 'user/view',
                'addresses' => 'address/index',
                'address/<id:\d+>' => 'address/view',
                'shippers' => 'shipper/index',
                'shipper/<id:\d+>' => 'shipper/view',

                'countries' => 'country/index',
                'country/<id:\d+>' => 'country/view',
                'cities' => 'city/index',
                'citiy/<id:\d+>' => 'city/view',
                'banks' => 'bank/index',
                'bank/<id:\d+>' => 'bank/view',
                'accounts' => 'account/index',
                'account/<id:\d+>' => 'account/view',

                'requests' => 'request/index',
                'request/<id:\d+>' => 'request/view',
                'quotations' => 'quotation/index',
                'quotations/<id:\d+>' => 'quotation/index',
                'quotation/<id:\d+>' => 'quotation/view',
                'orders' => 'order/index',
                'orders/<id:\d+>' => 'order/index',
                'order/<id:\d+>' => 'order/view',
                'payments' => 'payment/index',
                'payments/<id:\d+>' => 'payment/index',
                'payment/<id:\d+>' => 'payment/view',
                'deliveries' => 'delivery/index',
                'deliveries/<id:\d+>' => 'delivery/index',
                'delivery/<id:\d+>' => 'delivery/view',

                'employees/<id:\d+>' => 'entity-person-role/index',
                'employee/<id:\d+>' => 'entity-person-role/view',
                'employee/<_a:[\w\-]+>' => 'entity-person-role/<_a>',
                'employee/<_a:[\w\-]+>/<id:\d+>' => 'entity-person-role/<_a>',

                'roles/<id:\d+>' => 'entity-role/index',
                'role/<_a:[\w\-]+>' => 'entity-role/<_a>',
                'role/<id:\d+>' => 'entity-role/view',
                'role/<_a:[\w\-]+>/<id:\d+>' => 'entity-role/<_a>',

                'persons/<id:\d+>' => 'person/index',

                '<_c:[\w\-]+>/<_a:[\w\-]+>' => '<_c>/<_a>',
                '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
                '<_c:[\w\-]+>' => '<_c>/index',
                '<_c:[\w\-]+>/<_a:[\w\-]+>/<id:\d+>' => '<_c>/<_a>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'nullDisplay' => '',
            'dateFormat' => 'php:d.m.Y',

        ],
        'cart' => [
            'class' => 'yz\shoppingcart\ShoppingCart',
            'cartId' => 'my_cart',
        ],
        'innFinder' => [
            'class' => 'app\components\innfinder\InnFinder',
        ],
        'topMenu' => [
            'class' => 'app\components\topmenu\TopMenu',
        ],
        'view' => [
           // 'theme' => [
                //'pathMap' => [
                    //'@app/views' => '@vendor/dmstr/yii2-adminlte-asset/example-views/yiisoft/yii2-app'
                //],
           // ],
        ],
        'pdf' => [
            'class' => Pdf::classname(),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_DOWNLOAD,
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource'
                ],
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
    ];
}

if (isset($configLocal) && is_array($configLocal)) {
    $config = ArrayHelper::merge($config, $configLocal);
}

return $config;
