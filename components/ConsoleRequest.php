<?php
/**
 * User: xakki
 * Date: 10.11.15
 */

namespace app\components;

if (empty($_SERVER['SERVER_NAME'])) $_SERVER['SERVER_NAME'] = 'console';

use Yii;
use \yii\console\Application;

class ConsoleRequest extends \yii\console\Request
{

    /**********************/

    public function getHostInfo()
    {
        return '';
    }
    protected function resolveRequestUri()
    {
        return getcwd().DIRECTORY_SEPARATOR.$_SERVER['PHP_SELF'];
//        [argv] => Array
//        (
//            [0] => yii
//            [1] => tasker/stats
//        )

    }
}
