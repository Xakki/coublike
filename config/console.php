<?php

use yii\helpers\ArrayHelper;

$configEnv =  require(__DIR__ . 'sub/config.'.YII_ENV.'.php');
$params = require(__DIR__ . '/sub/params.php');
$components = ArrayHelper::merge(require(__DIR__ . '/sub/components.php'), require(__DIR__ . '/sub/console_components.php'));
$modules = require(__DIR__ . '/sub/modules.php');

$components['log']['targets'][0]['logVars'] = [];

$config = [
    'id' => 'console',
    'name'=> 'COUBLIKE',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Moscow',
    'language' => LOC_LANG,
    'controllerNamespace' => 'app\commands',
    'modules' => $modules,
    'components' => $components,
    'params' => $params,
    'aliases' => [
        '@tests' => dirname(__DIR__) . '/tests',
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
//        '@npm' => '@vendor/npm-asset',
    ],

];

$config = ArrayHelper::merge($config, $configEnv);

return $config;