<?php

namespace app\modules\Admin\models;

use app\models\User as BaseUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\UserPay\models\UserPay as UserPayModel;
use yii\helpers\ArrayHelper;

/**
 * UserPay represents the model behind the search form about `\app\modules\UserPay\models\UserPay`.
 */
class User extends BaseUser
{

    public static function getUserList() {

        $list = self::find()
            ->select(['id', 'CONCAT(id, ": ", username) as username'])
            ->asArray(true)
            ->all();

        return ArrayHelper::map($list, 'id', 'username');
    }
}
