<?php

namespace app\modules\Support\controllers;

use Yii;
use Exception;
use yii\helpers\Url;
use app\modules\Support\models\Messages;

class AdminController extends \yii\web\Controller
{
    public $isBackend = true;
    const STATUS_ERROR = 0;
    const STATUS_OK = 1;

    public function actionIndex()
    {
        $iam = Messages::USER_SUPPORT;
        $uid = 0;
        if (!empty($_GET['uid'])) {
            $uid = (int)$_GET['uid'];
        }

        if ($uid && Yii::$app->request->getIsPost() && $newIds = Yii::$app->request->post('newIds')) {
            $flag = Messages::setReadMessages($iam, $newIds);
            if (Yii::$app->request->isAjax) {
                return json_encode(['status'=> self::STATUS_OK, 'mess' => 'Success read : '.$flag]);
            }
        }

        if ($uid) {
            $model = new Messages(['scenario' => 'admin']);

            try {
                if ($model->load(Yii::$app->request->post())) {
                    // form inputs are valid, do something here
                    $model->setIsNewRecord(true);
                    $model->user_to = $uid;
                    $model->user_from = $iam;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', Yii::t('support', 'Success send messages'));
                        return $this->redirect(Url::to());
                    } else {
                        //Yii::$app->session->setFlash('danger', 'Error insert data');
                    }

                }
            }
            catch (Exception $e) {
                Yii::$app->session->setFlash('success', $e->getMessage());
            }
        }

        $renderData = [];

        if ($uid && !empty($model)) {
            $renderData = Messages::getMessagesList($iam, $uid);
            $renderData['modelForm'] = $model;
        }
        $renderData += Messages::getDialogList($iam);

        return $this->render('index', $renderData);
    }

}
