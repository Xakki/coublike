<?php

return  [
    // https://github.com/dektrium/yii2-user/blob/master/docs/README.md
    'user' => [
        'class' => 'dektrium\user\Module',
//        'layout' => '/dashboard',
        'admins' => [
            1 => true,
            2 => true,
        ],
        'controllerMap' => [
            'admin' => [
                'class'  => 'app\controllers\DisableController',
            ],
            'profile' => [
                'class'  => 'app\controllers\DisableController',
            ],
            'recovery' => [
                'class'  => 'app\controllers\DisableController',
            ],
            'registration' => [
                'class'  => 'app\controllers\DisableController',
            ],
//            'security' => [
//                'class'  => 'app\controllers\DisableController',
//            ],
            'settings' => [
                'class'  => 'app\controllers\UserSettingsController',
            ],
        ],
        'modelMap' => [
            'User' => 'app\models\User',
            'Account' => 'app\models\Account',
        ],
        'mailer' => [
            'class' => 'app\models\Mailer',
            'sender'                => 'noreply@'.HOST, // or ['no-reply@myhost.com' => 'Sender name']
            'welcomeSubject'        => 'Welcome subject',
            'confirmationSubject'   => 'Confirmation subject',
            'reconfirmationSubject' => 'Email change subject',
            'recoverySubject'       => 'Recovery subject',
        ],
//        'urlRules' => [
//            '/user/settings/networks'                  => '/site/networks'
//        ]
//        'on afterLogin' => function($event) {
//            Yii::$app->user->identity->afterLogin($event);
//        }
    ],
    'userpay' => [
        'class' => '\app\modules\UserPay\Module',
        'curs' => 20,
        'minCost' => 10,
        'pack' => [
            50000 => 25,
            20000 => 20, // лайки => + %бонус
            10000 => 15,
            5000 => 10,
            3000 => 8,
            1000 => 5,
            400 => 0
        ],
        'payments' => [
            'qiwi' => [
//                'class' => 'Qiwi',
                'providerId' => '00000',
                'apiId' => '999999',
                'apiKey' => '*************',
                'notifyKey' => '***************'
            ],
            'yandex' => [
//                'class' => 'Yandex',
                //        'testMode' => true, // https://tech.yandex.ru/money/doc/payment-solution/examples/examples-payment-docpage/
                    'url_new_token' => '/userpay/income/new-token-yandex',
                'account' => '00000000000', 
                'password' => '****************',
                'method' => 'PC',
                'access_token' => '*****************',
                'expire_token' => '2028-12-28', // date by expire this token // 3 years
                'client_id' => 'ABCABCABCABCABCABCABCABCABCABCABCABC',
                'client_secret' => 'XYZXYZXYZXYZXYZXYZXYZXYZXYZXYZXYZXYZXYZXYZ',
            ]
        ]
    ],
    'support' => [
        'class' => '\app\modules\Support\Module',
        'layout' => '/dashboard'
    ]
];