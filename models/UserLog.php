<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class UserLog
 * @package app\models
 * @property integer $ul_id
 * @property integer $ul_type
 * @property integer $ul_time
 * @property integer $ul_user_id
 * @property string  $ul_log
 * @property string  $ul_int
 * @property string  $ul_int2
 * @property bool    $ul_flag
 */
class UserLog extends ActiveRecord
{
    const TYPE_EARN_LIKES = 1; // likes, taskId
    const TYPE_BUY_LIKES = 2; // likes, payId
    const TYPE_BUY_LIKES_BONUS = 3; // likes, payId
    const TYPE_REF_EARN = 4; // likes, refUserId
    const TYPE_REF_BUY = 5; // likes, refUserId
    const TYPE_EVERY_DAY = 6; // likes, refUserId
    const TYPE_OVER = 10;


    public static function tableName()
    {
        return 'user_log';
    }

    public static function addLog($type, $user_id = null, $logInt = null, $logInt2 = null, $logStr = null) {
        $model = new self();
        if (!is_null($logInt)) {
            $model->ul_int = $logInt;
        }
        if (!is_null($logInt2)) {
            $model->ul_int2 = $logInt2;
        }

        if (!is_null($logStr)) {
            $model->ul_log = $logStr;
        }
        $model->ul_type = $type;
        $model->ul_time = time();
        $model->ul_user_id = ($user_id ? $user_id : Yii::$app->user->id);
        return $model->save();
    }

}