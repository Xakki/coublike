<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'css/dashboard.css',
        'css/font-awesome.min.css',
    ];
    public $js = [
        'js/dashboard.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    /**
     * @param \yii\web\View $view
     * @return AssetBundle
     */
    public static function register($view)
    {
        $view->registerLinkTag(['rel' => 'icon', 'type' => 'image/ico', 'href' => '/favicon.ico']);
        return parent::register($view);
    }

//    public function init()
//    {
//        parent::init();
//        $this->js[] = 'i18n/' . Yii::$app->language . '.js'; // dynamic file added
//    }
}
