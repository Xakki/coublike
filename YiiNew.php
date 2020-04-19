<?php
/**
 * Yii bootstrap file.
 *
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

require(__DIR__ . '/vendor/yiisoft/yii2/BaseYii.php');

/**
 * @property \app\components\GoogleApi $gooapi
 * @property \yii\authclient\Collection $authClientCollection
 * @property \UserNew $user
 */
class ApplicationNew extends yii\web\Application
{

}

class Yii extends \yii\BaseYii
{
    /**
     * @var ApplicationNew
     */
    public static $app;
}

/**
 * Class User
 * @property \app\models\User $identity
 */
class UserNew extends \yii\web\User
{
    /**
     * @var ApplicationNew
     */
    public static $app;
}

spl_autoload_register(['Yii', 'autoload'], true, true);
Yii::$classMap = require(__DIR__ . '/vendor/yiisoft/yii2/classes.php');
Yii::$container = new yii\di\Container();
