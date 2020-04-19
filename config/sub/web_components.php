<?php

return  [
    'request' => [
        'cookieValidationKey' => '*************',
        'enableCookieValidation' => true,
        'enableCsrfValidation' => true,
//        'parsers' => [
//            'application/json' => 'app\components\JsonParser',
//        ]
    ],
//    'response' => [
//        'parsers' => [
//            'application/json' => 'app\components\JsonParser',
//        ]
//    ],
    'session' => [
        'class' => 'yii\web\DbSession',
        'cookieParams' => [
//            'domain' => '.'.$_SERVER['SERVER_NAME'],
            'httponly' => true,
            'lifetime' => 86400*7
        ],
        'timeout' => 86400*30,
        //'useCookies' => true,
    ],
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'urlManager' => [
        'class' => '\app\components\UrlManager',
        'enablePrettyUrl' => true,
        'showScriptName' => false,
//        'enableStrictParsing' => false,
//            'suffix' => '.html',
        'rules' => [
            '/createpay/<ps:\w+>/<likes:\d+>'           => '/userpay/default/create',
            '/userpay/<likes:\d+>'                      => '/userpay/default/index',
            '/userpay/init/<id:\d+>'                    => '/userpay/default/init',
            '/userpay/history'                          => '/userpay/default/history',
            '/userpay/info/<id:\d+>'                    => '/userpay/default/info',
            '/userpay/success/<id:\d+>'                 => '/userpay/default/success',
            '/userpay/fail/<id:\d+>'                    => '/userpay/default/fail',
            '/userpay/confirm/<ps:\w+>'                 => '/userpay/income/confirm',
//            '/site/login'                               => '/user/auth?authclient=coub',

            ADMIN_URL.'/support/index' => 'support/admin/index',
            'dashboard/support' => 'support/user/index',

            ADMIN_URL => 'admin/default/index',
            ADMIN_URL.'/<controller>/<action>' => '/admin/<controller>/<action>',
            ADMIN_URL.'/user/<action>' => '/user/admin/<action>',
            ADMIN_URL.'/user/<action>' => '/user/user/<action>',
            //'user/<action:\w+>' => 'user/settings/<action>',
        ],
//        'urlRewrite' => [
//            '/user/settings/networks' => '/site/networks',
//        ]
    ],
    'view' => [
        'theme' => [
            'pathMap' => [
//                '@dektrium/user/views/registration' => '@app/views/user/registration',
                '@dektrium/user/views/settings' => '@app/views/user/settings',
//                '@dektrium/user/views' => '@app/views/user'
//                '@dektrium/user/views/admin' => '@app/modules/Admin/views/user', // TODO: move to module Admin
            ],
        ],
    ],
    'assetManager' => [
        'appendTimestamp' => true
    ]
];