<?php

use yii\helpers\ArrayHelper;

$paramsLocal = file_exists(__DIR__ . '/params-local.php')? include(__DIR__ . '/params-local.php'): [];

$params = [
    'name' => 'Склад болтов',
    'domain' => 'http://skladboltov.ru',
    'adminEmail' => 'info@skladboltov.ru',
];

if (isset($paramsLocal) && is_array($paramsLocal)) {
    $params = ArrayHelper::merge($params, $paramsLocal);
}
