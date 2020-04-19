<?php

use yii\helpers\ArrayHelper;

//session_set_cookie_params(3600*24*30*12, '/', '.'.$_SERVER['SERVER_NAME'], 0);
//ini_set('session.cookie_domain',  '.'.$_SERVER['SERVER_NAME']);

$configEnv =  require(__DIR__ . 'sub/config.'.YII_ENV.'.php');
$params = require(__DIR__ . '/sub/params.php');
$components = ArrayHelper::merge(require(__DIR__ . '/sub/components.php'), require(__DIR__ . '/sub/web_components.php'));
$modules = require(__DIR__ . '/sub/modules.php');

$modules['admin'] = [
    'class' => '\app\modules\Admin\Module',
    'layout' => '/dashboard'
];

$config = [
    'id' => 'basic',
    'version' => '0.1',
//    'layout' => 'dashboard',
    'name'=> 'COUBLIKE',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Europe/Moscow',
    'language' => LOC_LANG,
    'components' => $components,
    'container' => [
        'definitions' => [
            'yii\log\Logger' => '\xakki\phperrorcatcher\connector\YiiLogger',
        ]
    ],
    'modules' => $modules,
    'params' => $params,
    'aliases' => [
        '@bower' => '@vendor/yidas/yii2-bower-asset/bower',
//        '@npm' => '@vendor/npm-asset',
    ],
];

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '*']
    ];
}

$config = \yii\helpers\ArrayHelper::merge($config, $configEnv);

return $config;
