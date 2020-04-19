<?php

namespace app\models;

use dektrium\user\models\Token;
use dektrium\user\Mailer as BaseMailer;
use dektrium\user\models\User as BaseUser;

/**
 * Class Mailer
 * @package app\models
 * @property integer $ul_id
 * @property integer $ul_type
 * @property integer $ul_time
 * @property integer $ul_user_id
 * @property string  $ul_log
 */
class Mailer extends BaseMailer
{

    public function sendWelcomeMessage(BaseUser $user, Token $token = null, $showPassword = false)
    {
        return true;
//        return parent::sendWelcomeMessage($user, $token, $showPassword);
    }
//    protected function sendMessage($to, $subject, $view, $params = [])
//    {
//        return true;
//        if (!$to) {
//            return true;
//        }
//        return parent::sendMessage($to, $subject, $view, $params);
//    }
}