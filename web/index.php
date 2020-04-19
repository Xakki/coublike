<?php

if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//    header('Access-Control-Allow-Origin: *');
//    header('Access-Control-Allow-Headers: X-Requested-With');
    header("HTTP/1.1 200 OK");
    die();
}

require(__DIR__ . '/../vendor/autoload.php');


// comment out the following two lines when deployed to production
if (file_exists(__DIR__.'/../.isLocal')) {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_ENV') or define('YII_ENV', 'dev');
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', (isset($_COOKIE['mycdebug']) ? true : false));
    defined('YII_ENV') or define('YII_ENV', 'prod');
}

if (YII_DEBUG) {
    ini_set("memory_limit", "128M");
    ini_set('max_execution_time', 600);
} else {
    ini_set("memory_limit", "32128M");
    ini_set('max_execution_time', 60);
}

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');


function _e($value)
{
    if (!$value) return $value;
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_IGNORE);
}

(new yii\web\Application($config))->run();
