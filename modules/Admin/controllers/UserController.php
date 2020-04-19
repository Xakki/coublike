<?php

namespace app\modules\Admin\controllers;

use dektrium\user\Finder;

class UserController extends \dektrium\user\controllers\AdminController
{
    // public $layout = 'dashboard';
    public $isBackend = true;

    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $module2 = \Yii::$app->getModule('user');
        $module2->layout = $module->layout;
        parent::__construct($id, $module2, $finder, $config);
    }

    public function beforeAction($action)
    {
        parent::setViewPath('@dektrium/user/views/admin/');
        return parent::beforeAction($action);
    }
}
