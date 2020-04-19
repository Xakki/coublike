<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class UserSettingsController extends \dektrium\user\controllers\SettingsController
{
    use \app\traits\dashboardController;
    public $layout = '@app/views/layouts/dashboard';
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
//                    'disconnect' => ['post', 'get'],
                    'delete'     => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['networks', 'disconnect'],//'profile', 'account', 'delete'
                        'roles'   => ['@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['confirm'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }
}
