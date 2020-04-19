<?php

$configComponents =  [

//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//        ],

    'mailer' => [
        'class' => 'yii\swiftmailer\Mailer',
        // send all mails to a file by default. You have to set
        // 'useFileTransport' to false and configure a transport
        // for the mailer to send real emails.
//        'useFileTransport' => true,
    // https://yandex.ru/support/mail/troubleshooting/create-send.xml
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.yandex.ru',
            'username' => 'noreply@'.HOST,
            'password' => 'BIDL6Bbgf133dNzxp',
            'port' => '587',
            'encryption' => 'tls',
        ],
    ],
    'log' => [
        'flushInterval' => 10,
        'traceLevel' => YII_DEBUG ? 2 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
//                'maxLogFiles' => 10,
                'levels' => YII_DEBUG ? ['error', 'warning'/*, 'trace'*/] : ['error', 'warning'],
                'logVars' => ['_SERVER.REQUEST_URI', '_GET', '_POST'],//, '_FILES', '_COOKIE', '_SESSION'
                'logFile' => '@runtime/logs/app.log',
                'except' => [
                    'yii\web\HttpException:404',
                ]
            ],
            [
                'class' => '\app\components\SimpleFileTarget',
                'levels' => ['error', 'warning'],
                'logVars' => ['_SERVER.REQUEST_URI'],//, '_FILES', '_COOKIE', '_SESSION'
                'logFile' => '@runtime/logs/404.log',
                'categories' => [
                    'yii\web\HttpException:404',
                ],
            ]
        ],
    ],
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=coublike',
        'username' => 'coublike',
        'password' => '************',
        'charset' => 'utf8',
    ],
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'dateFormat' => 'php:j M Y',
        'datetimeFormat' => 'php:j M Y H:i',
        'timeFormat' => 'php:H:i',
        'timeZone' => 'UTC',
    ],
    // https://yiiframework.com.ua/ru/doc/guide/2/tutorial-i18n/
    'i18n' => [
        'translations' => [
            'app*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                //'basePath' => '@app/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'form'      => 'form.php',
                    'app'       => 'app.php',
                    'app/model' => 'model.php',
                    'app/error' => 'error.php',
                ],
                'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
            ],
            '*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                //'basePath' => '@app/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'form'      => 'form.php',
                    'app'       => 'app.php',
                    'app/model' => 'model.php',
                    'app/error' => 'error.php',
                ],
                'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
            ],
//            'yii' => [
//                'class' => 'yii\i18n\PhpMessageSource',
//                'sourceLanguage' => 'en-US',
//                    'basePath' => '@app/messages'
//            ],
        ],
    ],
    'gooapi' => [
        'class' => '\app\components\GoogleApi',
        'apiKey' => '*********************',
    ],
    'authClientCollection' => [
        'class' => yii\authclient\Collection::class,
        'clients' => [
            'coub' => [
                'class'        => '\app\modules\Couber\authclients\CoubAuth',
                // coublike.ru
                // http://coub.com/coublikeru
                // Почта coublikeru@yandex.ru :  coublikeru19385
                // COUb  coublikeru@yandex.ru :  coublikeru47893

                // coublike.com
                // http://coub.com/coublikecom
                // Почта coublikecom@yandex.ru : coublikeru19379
                // COUb  coublikecom@yandex.ru : coublikeru47848
                'clientId'     => '*********************',
                'clientSecret' => '*********************',
            ],
//            'facebook' => [
//                'class'        => 'dektrium\user\clients\Facebook',
//                'clientId'     => 'CLIENT_ID',
//                'clientSecret' => 'CLIENT_SECRET',
//            ],
        ],
    ]
];

return $configComponents;