<?php

namespace app\modules\Support\models;

use app\models\User;
use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property integer $id
 * @property string  $user_from
 * @property string  $del_from
 * @property integer $user_to
 * @property integer $del_to
 * @property string  $read  Время прочтения
 * @property string  $created
 * @property string  $mess
 */
class Messages extends \yii\db\ActiveRecord
{
    const USER_SUPPORT = 0;
    const LIMIT_DIALOG = 20;
    const LIMIT_MESSAGES = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{support_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mess'], 'required', 'on' => ['support', 'admin']],
            [['mess'], 'string', 'max' => 255, 'on' => ['support', 'admin']],
            [['user_from', 'user_to', 'created'], 'safe', 'on' => ['support', 'admin']]
        ];
    }
    public function beforeValidate() {
        if ($this->getScenario()=='admin') {
        } else {
            $this->user_from =  Yii::$app->user->getId();
            $this->user_to = 0;
        }

        $this->created = new \yii\db\Expression('CURRENT_TIMESTAMP');
        return parent::beforeValidate();
    }


    public static function addSupportMessages($text) {
        $model = new self();
        $model->mess = $text;
        return $model->save();
    }

    /**
     * @param $iam
     * @param $idList
     * @return int
     */
    public static function setReadMessages($iam, $idList) {
        if (count($idList)) {
            $idList = array_map('intval', $idList);
            $idList = array_combine($idList, $idList);
            return self::updateAll(['read' => new \yii\db\Expression('CURRENT_TIMESTAMP')], ['id' => $idList, 'user_to' => $iam]);
        }
        return 0;
    }


    /**
     * Messages
     * @param $iam
     * @param $uid
     * @return array
     */
    public static function getMessagesList($iam, $uid)
    {
        $query = self::getSupportMessagesQuery($iam, $uid);

        $query->orderBy('id desc');

        $countQuery = clone $query;
        $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => self::LIMIT_MESSAGES]);
        $pages->pageSizeParam = false;

        $data = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return ['messageList' => $data, 'messagePage' => $pages, 'iam' => $iam, 'uid' => $uid, 'iamName' => self::getUserName($iam), 'uidName' => self::getUserName($uid)];
    }


    public static function getCountUnread($iam, $uid) {
        $query = self::find();
        $query->andOnCondition('user_from=:uid and del_from IS NULL and user_to=:iam and del_to IS NULL and `read` IS NULL', [':iam' => $iam, ':uid' => $uid]);
        return $query->count();
    }

    public static function getUserName($uid) {
        if ($uid==self::USER_SUPPORT) {
            return Yii::t('app', 'Support');
        }
        elseif ($uid==Yii::$app->user->getId()) {
            return Yii::$app->user->getId();
        }
        else {
            return 'TODO+++';
        }
    }

    public static function getSupportMessagesQuery($iam, $uid)
    {
        $query = self::find();
        $query->andOnCondition('(user_from=:iam and del_from IS NULL and user_to=:uid and del_to IS NULL) or (user_from=:uid and del_from IS NULL and user_to=:iam and del_to IS NULL) ', [':iam' => $iam, ':uid' => $uid]);
        return $query;
    }

    /**
     * Dialog list
     * @param int $iam
     * @param array $idList
     * @return array
     */
    public static function getDialogList($iam, $idList = array())
    {

        $query = self::getCriteriaDialogList($iam);
        if (count($idList)) {
            $query->andWhere(['not in','id', $idList]);
        }

        $totalCount = $query->count();

        $data = $query
            ->limit(self::LIMIT_DIALOG)
            ->with('user')
            ->asArray(true)
            ->all();
        return ['dialogList' => $data, 'dialogTotalCount' => $totalCount, 'dialogPageSize' => self::LIMIT_DIALOG, 'iam' => $iam];
    }


    public static function getCriteriaDialogList($uid = null)
    {
        if (is_null($uid)) {
            $uid = Yii::$app->user->id;
        }
        $query = self::find();
        ///(extract(epoch from `read`) * 1000)
        $query->select(['CASE WHEN user_from=:uid THEN user_to ELSE user_from END as uid, count(id) as cnt,
                max( created ) as crt,
                max( (CASE WHEN user_from=:uid and `read` IS NULL THEN ' . PHP_INT_MAX . ' WHEN user_from=:uid and `read` IS NOT NULL THEN  `read` ELSE 0 END) ) as readmess,
                sum( (CASE WHEN user_to=:uid and `read` IS NULL THEN 1 ELSE 0 END) ) as new'])
            ->andOnCondition('(user_from=:uid and del_from IS NULL) or (user_to=:uid and del_to IS NULL) ', [':uid' => (int)$uid])
            ->orderBy('crt DESC')
            ->groupBy('uid');
        return $query;
    }

    /**
     * RELATION to last data
     * @return $this
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'uid']);
    }

    /**
     * TODO
     * @param $overUser
     * @param null $uid
     * @param null $lastId
     * @param null $firstId
     * @return \yii\db\ActiveQuery
     */
    public static function getCriteriaMessagesList2($overUser, $uid = null, $lastId = null, $firstId = null)
    {
        if (!$uid) {
            $uid = Yii::$app->user->id;
        }
        $query = self::find();
        $query->select('t.*, CASE WHEN t.from_uid=:uid THEN t.to_uid ELSE t.from_uid END as uid')
            ->onCondition( '( (from_uid=:uid and from_del IS NULL and to_uid=:oid) or (to_uid=:uid and to_del IS NULL and from_uid=:oid) )', [':uid' => (int)$uid, ':oid' => (int)$overUser])
            ->orderBy('id DESC')
            ->limit(self::LIMIT_MESSAGES)
            ->asArray(true);


        if ((int)$lastId) {
            $query->andOnCondition('id>:last_id', [':last_id' => (int)$lastId]);
        }

        if ((int)$firstId) {
            $query->andOnCondition('id<:first_id', [':first_id' => (int)$firstId]);
        }
        return $query;
    }

}
