<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\base\Exception;


class Controller extends \yii\web\Controller
{
    use \app\traits\dashboardController;

    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

//    public function runAction($id, $params = [])
//    {
//        try {
//            return parent::runAction($id, $params);
//        } catch (Exception $e) {
//            $this->render(404, []);
//            //throw $e;
//        }
//    }


    public function render($view, $params = [])
    {
        if (Yii::$app->request->isAjax) {
            return json_encode($params);
        }
        elseif ($view=='') {
            if (isset($params['redirect'])) {
                $redirect = Url::to($params['redirect']);
            }
            else {
                $redirect = Yii::$app->request->referrer;
            }

            Yii::$app->session->setFlash( ($params['status']==self::STATUS_OK ? FLASH_OK : FLASH_ERROR), $params['mess']);

            return $this->redirect($redirect);
        }
        return parent::render($view, $params);
    }

    public function needCoubConection($mess = '') {
        $result = ['status'=> self::STATUS_ERROR, 'mess' => 'Need connect COUB '.$mess, 'redirect' => '/site/networks'];
        $this->render('', $result);
        return false;
    }

    public function runAction($id, $params = []) {
        try {
            return parent::runAction($id, $params);
        }
        catch (\app\exception\ExceptionReConnect $e) {
            Yii::$app->session->setFlash(FLASH_ERROR, Yii::t('app', 'Need reconnect COUB account'));
            return $this->redirect(Url::to(['/user/settings/networks']));
        }
        catch (Exception $e) {
            throw $e;
        }
    }
}
