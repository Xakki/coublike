<?php
namespace app\modules\Support;

use Yii;

class Module extends \yii\base\Module
{
    const VERSION = '0.0.1';

    private $_assetsUrl, $_assetsPath;

    public function init()
    {
        if (\Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('danger', 'Access denied');
            return \Yii::$app->response->redirect('/');
        }
        list($this->_assetsPath, $this->_assetsUrl) = Yii::$app->getAssetManager()->publish(__DIR__ . '/assets');
        return parent::init();
    }

    public function getAssetsUrl()
    {
        return $this->_assetsUrl;
    }
}
