<?php

namespace app\modules\UserPay\controllers;

use \app\controllers\Controller;
use \app\modules\UserPay\models\UserPay;
use \Yii;

/**
 * Class IncomeController
 * @package app\modules\UserPay\controllers
 * @property \app\modules\UserPay\Module $module
 */
class IncomeController extends Controller
{
    public $enableCsrfValidation = false;


    public function actionConfirm($ps)
    {
        if (!$this->module->hasPayment($ps)) {
            return 'Error pay system';
        }
        return $this->module->notifyPayment($ps);
    }


    public function actionNewTokenYandex()
    {
        if (!$this->module->hasPayment('payyandex')) {
            return 'Error pay system';
        }
        $payComponent = $this->module->getPayment('payyandex');
        /* @var $payComponent \app\modules\UserPay\components\Yandex */
        $payComponent->getAccessToken();
    }
}
