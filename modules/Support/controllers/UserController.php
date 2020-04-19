<?php

namespace app\modules\Support\controllers;

use Yii;
use yii\helpers\Url;
use app\modules\Support\models\Messages;

class UserController extends \yii\web\Controller
{
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            $this->redirect('/');
            return false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $iam = Yii::$app->user->id;
        $uid = Messages::USER_SUPPORT;
        if (Yii::$app->request->getIsPost() && $newIds = Yii::$app->request->post('newIds')) {
            $flag = Messages::setReadMessages($iam, $newIds);
            if (Yii::$app->request->isAjax) {
                return json_encode(['status'=> self::STATUS_OK, 'mess' => 'Success read :'.$flag]);
            }
        }

        $model = new Messages(['scenario' => 'support']);

        try {
            if ($model->load(Yii::$app->request->post())) {
                // form inputs are valid, do something here
                $model->setIsNewRecord(true);
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('support', 'Success send messages'));
                    return $this->redirect(Url::to());
                }
                else {
                    //Yii::$app->session->setFlash(FLASH_ERROR, 'Error insert data');
                }

            }
        }
        catch (\Throwable $e) {
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        $list = Messages::getMessagesList($iam, $uid);
        $list['modelForm'] = $model;
        return $this->render('support', $list);
    }

    public static function getCountUnread() {
        if (Yii::$app->user->id) {
            $cnt = Messages::getCountUnread(Yii::$app->user->id, Messages::USER_SUPPORT);
            if ($cnt) {
                return ' +' . $cnt;
            }
        }
        return '';
    }
}
