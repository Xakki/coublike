<?php
/**
 * Couber by xakki
 */
namespace app\modules\Admin;

use Yii;
use yii\helpers\Url;
use yii\web\GroupUrlRule;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    public function init()
    {
//        parent::setViewPath('@dektrium/user/views/');
        if (\Yii::$app->user->isGuest || !Yii::$app->user->identity->getIsAdmin()) {
            Yii::$app->session->setFlash('danger', 'Access denied');
            return \Yii::$app->response->redirect('/');
        }
        if (empty($_COOKIE['mycdebug'])) {
            setcookie('mycdebug', time(), 9999999999, '/');

        }
        parent::init();
    }


}
