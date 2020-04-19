<?php

// http://www.yiiframework.com/doc-2.0/guide-tutorial-console.html

namespace app\commands;

use Yii;
use yii\console\Controller;
use \app\models\TaskSocial;
use yii\console\Exception;

class TaskerController extends Controller
{
    public function actionIndex() {
        echo 'Off'.PHP_EOL;
        return ;
    }

    public function actionStats() {
        echo PHP_EOL.date('Y-m-d H:i:s  ');
        echo 'Get stat from: ';
        TaskSocial::getActiveStatistic();
        echo PHP_EOL;
        echo 'Update off: ';
        TaskSocial::getOffStatistic();
        echo PHP_EOL;
        return ;
    }
    public function actionOff() {
        echo PHP_EOL.date('Y-m-d H:i:s  ');
        echo 'Set off Inactive: '.PHP_EOL;
        TaskSocial::setOffInactive();
        echo PHP_EOL;
        echo 'Set off NotSupport: ';
        TaskSocial::setOffNotSupport();
        echo PHP_EOL;
        return ;
    }
}
