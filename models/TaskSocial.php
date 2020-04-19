<?php

namespace app\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "tasker".
 *
 * @property integer $id
 * @property integer $time_cr
 * @property integer $time_up
 * @property integer $time_end
 * @property string $type
 * @property string $social
 * @property integer $status
 * @property integer $user_id
 * @property integer $group_id
 * @property integer $stats_mode
 * @property integer $stats_time
 * @property string $social_id
 * @property string $comment
 * @property integer $likes
 * @property integer $likes_spend
 * @property integer $likes_sum
 * @property integer $action_cost
 * @property integer $action_sum
 * @property string $social_link
 * @property string $social_link_tiny
 * @property TaskSocialInfo $tasker_info
 * @property TaskSocialAction $tasker_action
 * @property string $reason
 */
class TaskSocial extends \yii\db\ActiveRecord
{
    const TIMEOUT_EDIT = 300;
    const LIMIT_MIN_ACTIONS = 5;
    const LIMIT_MIN_COST = 2;

    const SOCIAL_COUB = 'coub';
    const SOCIAL_VK = 'vk';
    const SOCIAL_FB = 'fb';
    const SOCIAL_TW = 'tw';

    const STATUS_PAUSE = 0;
    const STATUS_ON = 1;
    const STATUS_COMPLETE = 2;
    const STATUS_DEL = 4;
    const STATUS_BLOCK = 5;

    const TYPE_FOLLOW = 'follow';
    const TYPE_REPOST = 'repost';
    const TYPE_LIKE = 'like';
    const TYPE_VIEW = 'view';
//    const TYPE_COMMENT = 'comment';

    private $_socialUrl = [
        'coub' => 'coub.com',
        'tw' => 'twitter.com',
        'fb' => 'facebook.com',
        'vk' => 'vk.com',
    ];

    private static $_minCost = [
        self::SOCIAL_COUB => [
            self::TYPE_VIEW => 1,
            self::TYPE_FOLLOW => 2,
            self::TYPE_REPOST => 2,
            self::TYPE_LIKE => 2,
        ]
    ];

    private static $_enumTypeAll = array(
        self::TYPE_VIEW => 'View',
        self::TYPE_LIKE => 'Like',
        self::TYPE_REPOST => 'Repost',
        self::TYPE_FOLLOW => 'Following',
    );

    private static $_enumType = array(
        self::TYPE_VIEW => 'View',
//        self::TYPE_LIKE => 'Like',
        self::TYPE_REPOST => 'Repost',
        self::TYPE_FOLLOW => 'Following',
    );

    private static $_enumTypeName;
    private static $_enumTypeNames;

    private $_social_data;
    private $action;

    public static function hasType($type)
    {
        return isset(self::$_enumType[$type]);
    }

    public static function getEnumType()
    {
        if (!self::$_enumTypeName) {
            self::$_enumTypeName = [];
            foreach (self::$_enumType as $k=>$r) {
                self::$_enumTypeName[$k] = Yii::t('app', $r);
            }
        }
        return self::$_enumTypeName;
    }

    public static function getEnumTypes()
    {
        if (!self::$_enumTypeNames) {
            self::$_enumTypeNames = [];
            foreach (self::$_enumType as $k=>$r) {
                self::$_enumTypeNames[$k] = Yii::t('app', $r.'`s');
            }
        }
        return self::$_enumTypeNames;
    }

    public function getNameType()
    {
        return self::$_enumTypeAll[$this->type];
    }

    public static function getTypes()
    {
        return self::$_enumType;
    }

    public static function getDataMinCost()
    {
        return self::$_minCost;
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasker';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['type', 'in', 'range' => array_keys(self::$_enumType), 'on' => ['insert']],
            ['type', 'required', 'on' => ['insert']],
            [['social_link', 'action_cost', 'action'], 'required', 'on' => ['insert', 'update']],
            [['social_link', 'comment'], 'string', 'max' => 255, 'on' => ['insert', 'update']],
            [['likes', 'likes_spend', 'social', 'status', 'group_id', 'social_link_tiny', 'social_id'], 'safe', 'on' => ['insert', 'update']],
            [['time_cr', 'user_id', 'stats_mode'], 'safe', 'on' => ['insert']],
            [['time_up', 'stats_mode'], 'safe', 'on' => ['update']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
            'social_link' => Yii::t('app', 'Link'),
            'action_cost' => Yii::t('app', 'Cost'),
            'action' => Yii::t('app', 'Action'),
            'comment' => Yii::t('app', 'Comments'),
            'likes' => Yii::t('app', 'Likes'),
//            'id' => Yii::t('app', 'ID'),
//            'time_cr' => Yii::t('app', 'Time create'),
//            'time_up' => Yii::t('app', 'Time update'),
//            'time_end' => Yii::t('app', 'Time end'),
//            'social' => Yii::t('app', 'Social network'),
//            'status' => Yii::t('app', 'Status'),
//            'user_id' => Yii::t('app', 'User'),
//            'group_id' => Yii::t('app', 'Group'),
//            'social_id' => Yii::t('app', 'Social Id'),
//            'social_data' => Yii::t('app', 'Social data'),
//            'likes_rsv' => Yii::t('app', 'Balance reserved'),
//            'likes_sum' => Yii::t('app', 'Balance sum'),
//            'social_link_tiny' => Yii::t('app', 'Link tiny'),
        ];

    }
    public function transactions()
    {
        return [
            'insert' => self::OP_INSERT,
            'update' => self::OP_UPDATE,
        ];
    }

    /**
     * RELATION to last data
     * @return $this
     */
    public function getTasker_info()
    {
        return $this->hasOne(TaskSocialInfo::class, ['tasker_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * RELATION to action
     * @return $this
     */
    public function getTasker_action()
    {
        return $this->hasOne(TaskSocialAction::class, ['ta_tasker_id' => 'id'])
            ->where('ta_user_id = :ta_user_id', [':ta_user_id' => Yii::$app->user->identity->id]);
    }

    public static function getMinCost(self $model) {
        if (isset(self::$_minCost[$model->social][$model->type])) {
            return self::$_minCost[$model->social][$model->type];
        }
        else {
            return self::LIMIT_MIN_COST;
        }
    }

    public function beforeValidate()
    {
        $social = self::SOCIAL_COUB;

//        $this->setAttribute('type', self::TYPE_LIKE);
        if ($this->isNewRecord) {
            $this->setAttribute('time_cr', time());
            $this->setAttribute('user_id', Yii::$app->user->getId());

        } else {
            $this->setAttribute('time_up', time());
        }

        $this->setAttribute('status', self::STATUS_ON);
        $this->setAttribute('group_id', 0);
        $this->setAttribute('social', $social);
        $this->setAttribute('likes_spend', 0); // обнуляем счетчик задачи

        $this->setAttribute('social_link', $this->getGoodUrl($this->social_link));

        if ($this->action < self::LIMIT_MIN_ACTIONS) {
            $this->addError('action', Yii::t('app', 'Minimum {0} likes', [self::LIMIT_MIN_ACTIONS]));
            return false;
        }
        $minCost = self::getMinCost($this);
        if ($this->action_cost < $minCost) {
            $this->addError('action_cost', Yii::t('app', 'Minimum {0} likes', [$minCost]));
            return false;
        }

        $this->likes = $this->action * $this->action_cost;

        if ($this->likes > Yii::$app->user->identity->getAttribute('likes')) {
            $this->addError('likes', Yii::t('app', 'Need {0} likes, but you have only {1}', [$this->likes, Yii::$app->user->identity->getAttribute('likes')]));
            return false;
        }

        if (!$this->validateTrueSocialUrl($this->social_link)) {
            $this->addError('social_link', Yii::t('app', 'Wrong URL domain. Need {0}', [$this->_socialUrl[$this->social]]));
            return false;
        }

//        $resUrlTiny = Yii::$app->gooapi->setTinyUrl($this->social_link);
//        if (isset($resUrlTiny->error)) {
//            if ($resUrlTiny->error->code == 403) {
//                $this->addError('social_link', Yii::t('app', 'A temporary error. Repeat submit chaser 1 hour.'));
//            } else {
//                $this->addError('social_link', Yii::t('app', 'Wrong URL')); // $resUrlTiny->error->message
//            }
//            return false;
//        }
//        $this->setAttribute('social_link_tiny', $resUrlTiny->id);

//        if (!Yii::$app->user->identity->getIsAdmin()) {
//            $this->addError('social_link', 'Профилактические работы! Скоро все заработает!');
//            return false;
//        }

        try {
            $coubResponse = $this->getSocialInfo();
        } catch (Exception $e) {
            Yii::warning($e->getMessage(), 'coub');
            $this->addError('social_link', Yii::t('app', 'This coub unavailable'));
            return false;
        }


        $this->setAttribute('social_id', $coubResponse->id);

        if ($this->isNewRecord) {
            $sameTask = $this->getSameTask();
            if ($sameTask) {
                $this->addError('social_link', Yii::t('app', 'You already have this task. Go to <a href="{0}">task #{1}</a>', ['/dashboard/task-edit?id='.$sameTask->id, $sameTask->id]));
//                $this->addError('social_link', Yii::t('app', 'You already have this task. Go to {0}', ['/dashboard/task-info?id='.$sameTask->id]));
                return false;
            }
        }

        if (!Yii::$app->user->identity->getIsAdmin()) {
            $userChannels = Yii::$app->user->identity->getChannelsId();
            if (!isset($userChannels[$coubResponse->channel_id])) {
                $this->addError('social_link', Yii::t('app', 'Its not you coub. Allow add only you channels coub!'));
                return false;
            }
        }

        $this->setStatsModeByTask();

        return parent::beforeValidate();
    }

//    public function afterValidate()
//    {
//        return parent::afterValidate();
//    }
    /**
     * Задаем режим сбора статистики в зависимости от суммы потраченного на задание
     */
    public function setStatsModeByTask() {
        $this->stats_mode = 1;
        $likes = $this->likes + $this->likes_spend + $this->likes_sum;
        foreach(Yii::$app->params['taskStatsMode'] as $k=>$r) {
            if ($likes>$k) $this->stats_mode = $r;
            else break;
        }
    }

    /**
     * @return \app\models\User
     */
    private function getTaskUser() {
        $user = \app\models\User::findIdentity($this->user_id);
        return $user;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $user = $this->getTaskUser();
            $rsvLikes = $this->likes;
            if (!$insert) {
                $rsvLikes = $rsvLikes - $this->getOldAttribute('likes');
            }
            if (!$user->reserveLikes($rsvLikes)) {
                throw new Exception(Yii::t('app', 'Error reserve Likes'));
            }
            return true;
        } else {
            return false;
        }
    }

//    public function afterSave($insert, $changedAttributes)
//    {
//        parent::afterSave($insert, $changedAttributes);
////        if ($this->_social_data) {
////            if (!TaskSocialData::addTaskStats($this, $this->_social_data)) {
////                throw new Exception(Yii::t('app', 'Error save task'));
////            }
////        }
//    }
//
//    public function save($runValidation = true, $attributeNames = null)
//    {
//
//        $connection = \Yii::$app->db;
//        $transaction = $connection->beginTransaction();
//        try {
//
//            if ($this->isNewRecord || $this->isMyTask()) {
//                $runValidation = true;
//            } else {
//                throw new Exception(Yii::t('app', 'Not Allow Function'));
//            }
//
//            if (!parent::save($runValidation, $attributeNames)) {
//                throw new Exception(Yii::t('app', 'Error save task'));
//            }
//
//            if ($this->_social_data) {
//                if (!TaskSocialData::addTaskData($this, $this->_social_data)) {
//                    throw new Exception(Yii::t('app', 'Error save task'));
//                }
//            }
//
//            $transaction->commit();
//            $res = true;
//        } catch (Exception $e) {
//            $transaction->rollback();
//            throw $e;
//        }
//        return $res;
//    }

    public function userDelete()
    {
        $this->status = self::STATUS_DEL;
        $this->likes = 0;
        return $this->save(false, ['status', 'likes']);
    }

    public function userReset()
    {
        $this->status = self::STATUS_COMPLETE;
        $this->likes = 0;
        return $this->save(false, ['status', 'likes']);
    }


    public function setTaskBlock($reason = null)
    {
        if ($reason) {
            $this->reason = $reason;
        }
        $this->status = self::STATUS_BLOCK;
        $this->likes = 0;
        return $this->save(false, ['status', 'likes', 'reason']);
    }

    public function beforeDelete()
    {
        throw new Exception('Forbidden delete function.');
    }

    public function validateTrueSocialUrl($url)
    {
        $link = parse_url($url);
        if (!$link || !$link['host'] || $link['host'] != $this->_socialUrl[$this->social]) {
            return false;
        }
        return true;
    }

    public function isComplete()
    {
        return ($this->status == self::STATUS_COMPLETE);
    }

    public function isDel()
    {
        return ($this->status == self::STATUS_DEL);
    }

    public function isBlocked()
    {
        return ($this->status == self::STATUS_BLOCK);
    }

    public function isRun()
    {
        return ($this->status == self::STATUS_ON);
    }

    public function isPause()
    {
        return ($this->status == self::STATUS_PAUSE);
    }

    public function isMyTask()
    {
        return (Yii::$app->hasProperty('user') && Yii::$app->user && $this->user_id == Yii::$app->user->id);
    }

    public function getTimeOut()
    {
        if ($this->time_up) {
            $t = time() - $this->time_up;
        } else {
            $t = time() - $this->time_cr;
        }
        return ($t < self::TIMEOUT_EDIT ? (self::TIMEOUT_EDIT - $t) : 0);
    }

    public function toggleTaskStatus($status = null)
    {
        if ($status === null) {
            $this->status = ($this->status == self::STATUS_ON ? self::STATUS_PAUSE : self::STATUS_ON);
        } else {
            $this->status = $status;
        }
        $this->time_up = time();
        return $this->save(false, ['status', 'time_up']);
    }

    /**
     * Optimal cost for ech type
     * @return array
     */
    public function optimalCost()
    {
        $query = self::getFreeLikeQuery();
        $query->select('type, avg(action_cost) as a, max(action_cost) as m')
            ->groupBy('type')
            ->asArray(true);
        $model = $query->all();
        $data = [];
        foreach ($model as $r) {
            $data[$r['type']] = (int) (($r['m'] + $r['a']) / 2 );
        }

        foreach ($this->getEnumType() as $k => $r) {
            if (!isset($data[$k])) {
                $data[$k] = 2;
            }
        }

        return $data;
    }

    public function getAction()
    {
        if ($this->action_cost && $this->likes) {
            return ($this->likes / $this->action_cost);
        }
        return '';
    }

    public function setAction($val)
    {
        $this->action = $val;
    }

    public function minUserLikeForTask()
    {
        return self::getMinCost($this) * self::LIMIT_MIN_ACTIONS;
    }

    public function canUserAddTask()
    {
        return ($this->minUserLikeForTask() < Yii::$app->user->identity->getAttribute('likes'));
    }


    public function getTaskAction()
    {
        // проверка , выполнянлась ли задача этим юзером
        return $this->tasker_action;
    }

    public function addTaskAction($data)
    {
        return TaskSocialAction::addTaskAction($this->id, $data);
    }

    public function addTaskActionIgnore()
    {
        return TaskSocialAction::addTaskActionIgnore($this->id);
    }


    /**
     * Get free like
     * @return bool|string
     * @throws Exception
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function setCompleteTaskFinish($aditionData)
    {
        if ($this->getTaskAction()) {
            return true;
        }

        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $newLikes = ($this->likes - $this->action_cost);

            if ($newLikes < 0) {
                throw new Exception('Error check sum in task');
            }

            $saveAttr = [
                'likes' => $newLikes, // вычитаем лайк у задачи
                'action_sum' => ($this->action_sum + 1), // прибавляем к общему колву задач
                'likes_spend' => ($this->likes_spend + $this->action_cost), // потраченые лайки в текуще итерации задачи
                'likes_sum' => ($this->likes_sum + $this->action_cost), // общее кол-во потраченых лайков на эту задачу
            ];
            if ($newLikes == 0) {
                $saveAttr['status'] = self::STATUS_COMPLETE;
            }

            if (!$this->updateAttributes($saveAttr)) {
                throw new Exception('Error update task');
            }
            // начисляем юзерю заработаные лайки
            if (!Yii::$app->user->identity->addLikes($this->action_cost, $this->id)) {
                throw new Exception('Error earn Likes');
            }

            if (!$this->addTaskAction($aditionData)) {
                throw new Exception('Error add task action');
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();
            if (YII_DEBUG) {
                throw $e;
            }
            return Yii::t('app', $e->getMessage());
        }
        return true;
    }

    /*************** ***************/
    /*************** ***************/
    /************ QUERY ************/
    /*************** ***************/
    /*************** ***************/


    /**
     * @return null|self
     */
    public function getSameTask()
    {
        $query = self::find();
        $query->andFilterWhere(['=', 'user_id', $this->user_id])
            ->andFilterWhere(['=', 'social_id', $this->social_id])
            ->andFilterWhere(['in', 'status', [self::STATUS_ON, self::STATUS_PAUSE, self::STATUS_COMPLETE]])
            ->andFilterWhere(['=', 'type', $this->type]);
        return $query->one();
    }

    public static function getMyTaskQuery()
    {
        $query = self::find();
        $query->andFilterWhere(['=', 'user_id', Yii::$app->user->id])
            ->andFilterWhere(['!=', 'status', self::STATUS_DEL]);
        return $query;
    }

    public static function getMyTaskQueryById($id)
    {
        $query = self::getMyTaskQuery();
        $query->andFilterWhere(['=', 'id', $id]);
        return $query;
    }

    public static function getFreeLikeQuery()
    {
        $query = self::find();
        $query
            ->andFilterWhere(['=', 'status', self::STATUS_ON])
            ->andFilterWhere(['>', 'likes', 0]);
        return $query;
    }

    public static function getAvailableFreeLikeQuery()
    {
        $query = self::getFreeLikeQuery();
        $query
            ->andFilterWhere(['!=', 'user_id', Yii::$app->user->id])
            ->leftJoin(TaskSocialAction::tableName(), 'ta_tasker_id = id and ta_user_id = :ta_user_id', [':ta_user_id' => Yii::$app->user->id])
            ->andWhere(['ta_id' => null])
            ->groupBy('id');
        if (isset($_GET['type'])) {
            $query->andWhere(['type' => $_GET['type']]);
        }
        return $query;
    }

    public static function getFreeLikeQueryById($id)
    {
        $query = self::getAvailableFreeLikeQuery();
        $query->andFilterWhere(['=', 'id', $id])
            ->with('tasker_info');
        return $query;
    }

    public static function getBestList($limit)
    {
        $viewList = [
            'likes' => Yii::t('app', 'By likes'),
            'new' => Yii::t('app', 'By New'),
        ];
        $mode = ( (empty($_GET['mode']) || !isset($modeList[$_GET['mode']])) ? 'likes': $_GET['mode']);

        $query = self::find();
        $query
            ->andWhere(['in','status', [self::STATUS_PAUSE, self::STATUS_ON, self::STATUS_COMPLETE]])
            ->andWhere(['>', 'likes_spend', 100])
            ->groupBy('social_id')
            ->orderBy('likes_spend desc')
            ->with('tasker_info');

        $countQuery = clone $query;
        $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $limit]);
        $pages->pageSizeParam = false;

        $data = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return ['data' => $data, 'pages' => $pages, 'mode' => $mode, 'viewList' => $viewList];
    }

    public static function getTaskList($limit, $type = 'my')
    {
        $modeList = [
            'active' => Yii::t('app', 'Active'),
            'off' => Yii::t('app', 'Off'),
        ];
        $mode = ( (empty($_GET['mode']) || !isset($modeList[$_GET['mode']])) ? 'active': $_GET['mode']);
        if ($type == 'free') {
            $query = self::getAvailableFreeLikeQuery();
            $query->orderBy('action_cost desc');
        } elseif ($type == 'my') {
            $query = self::getMyTaskQuery();
            if ($mode == 'off') {
                $query->andFilterWhere(['!=', 'status', self::STATUS_ON]);
            }
            else {
                $query->andFilterWhere(['=', 'status', self::STATUS_ON]);
            }
            $query->with('tasker_info')
                ->orderBy('id desc');
        } else {
            exit('Error Query type');
        }
        $countQuery = clone $query;
        $pages = new \yii\data\Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $limit]);
        $pages->pageSizeParam = false;

        $data = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->groupBy('id')
            ->all();
        return ['data' => $data, 'pages' => $pages, 'mode' => $mode, 'modeList' => $modeList];
    }

    public static function getFreeList($limit, $idList = array())
    {
        $query = self::getAvailableFreeLikeQuery();

        if (count($idList)) {
            $query->andWhere(['not in','id', $idList]);
        }

        $totalCount = $query->count();

        $query->orderBy('action_cost desc');

        $data = $query
            ->limit($limit)
            ->all();
        return ['data' => $data, 'totalCount' => $totalCount, 'pageSize' => $limit, 'type' => $_GET['type']];
    }
    /*************** ***************/
    /*************** ***************/
    /************ OVER *************/
    /*************** ***************/
    /*************** ***************/

    public function setCompleteTask()
    {

        if ($this->social == self::SOCIAL_COUB) {
            $additionData = '-';
            try {
                $coubApi = Yii::$app->user->identity->getCoubApi();
                // vew count
//                $additionData = Yii::$app->user->identity->getCoubApi()->apiSetView($this->getCoubCode());

                if ($this->type == self::TYPE_VIEW) {
//                    if (!is_numeric($additionData)) {
//                        return Yii::t('app', 'Coub : wrong view response');
//                    }
                    $additionData = true;
                } else{
                    $userResponse = $coubApi->apiUserMe();
                    if (!$userResponse->current_channel->id) {
                        return Yii::t('app', 'Wrong user data: No exist current channel');
                    }

                    if ($this->type == self::TYPE_LIKE) {
                        $coubResponse = $coubApi->apiSetLike($this->social_id, $userResponse->current_channel->id);
                        if ($coubResponse->status != 'ok') {
                            if ($coubResponse->status=='fail' && isset($coubResponse->validation_errors->channel_id[0])
                                && $coubResponse->validation_errors->channel_id[0] =='like has already been posted') {
                                // its OK
                                $this->addTaskActionIgnore();
                                return Yii::t('app', 'You already liked this');
                            } else {
                                if (YII_DEBUG) {
                                    print_r('<pre>');
                                    print_r($this->getAttributes());
                                    print_r($coubResponse);
                                    print_r('</pre>');
                                    exit();
                                }
                                return Yii::t('app', 'Wrong set like');
                            }
                        }
                        $additionData = $this->social_id;
                    } elseif ($this->type == self::TYPE_REPOST) {
                        $coubResponse = $coubApi->apiSetRecoub($this->social_id, $userResponse->current_channel->id);
                        if (isset($coubResponse->error) || !$coubResponse->id) {
                            if (YII_DEBUG) {
                                print_r('<pre>');
                                print_r($this->getAttributes());
                                print_r($coubResponse);
                                print_r('</pre>');
                                exit();
                            }
                            return Yii::t('app', 'Wrong repost this');//'You already repost this Coub'
                        }
                        $additionData = $coubResponse->id;
                    } elseif ($this->type == self::TYPE_FOLLOW) {
                        /* @var $social_data \app\modules\Couber\models\CoubBigJson */
                        if (empty($this->tasker_info)) {
                            try {
                                $social_data = $this->getSocialInfo();
                            } catch (Exception $e) {
                                Yii::warning($e->getMessage(), 'coub');
                                if (!$this->setTaskBlock('Coub is unavailable')) {
                                    throw new Exception('Error set block');
                                }
                                return Yii::t('app', 'This coub unavailable');
                            }
                        } else {
                            $social_data = json_decode($this->tasker_info->ti_info);
                        }
                        $coubResponse = $coubApi->apiSetFollow($social_data->channel_id, $userResponse->current_channel->id);
                        if ($coubResponse->status=='fail') {
                            $this->addTaskActionIgnore();
                            return Yii::t('app', 'You already follow this');
                        }
                        elseif ($coubResponse->status != 'ok') {
                            if (YII_DEBUG) {
                                print_r('<pre>');
                                print_r($this->getAttributes());
                                print_r($coubResponse);
                                print_r('</pre>');
                                exit();
                            }
                            return Yii::t('app', 'Wrong follow this');
                        }

                    } else {
                        return Yii::t('app', 'Wrong type');
                    }

                }

                return $this->setCompleteTaskFinish($additionData);
            } catch (Exception $e) {
//                if (YII_DEBUG) {
//                    throw $e;
//                }

                if (isset($e->responseBody) && strpos($e->responseBody, '{') === 0) {
                    $res = json_decode($e->responseBody, true);

                    if (!empty($res['errors'])) {
                        $res = $res['errors'];
                        if ($res == 'Validation failed: Channel like has already been posted' && $this->type == self::TYPE_LIKE) {
                            $res = $this->setCompleteTaskFinish($additionData);
                        }
                    } elseif (!empty($res['error'])) {
                        $res = $res['error'];
                    } else {
                        $res = $e->responseBody;
                    }
                    // Validation failed: Entity not found
                    // Validation failed: Channel like has already been posted
                } else {
                    $res = $e->getMessage();
                }
                return Yii::t('app', $res);
            }
        } else {
            return Yii::t('app', 'Wrong social type');
        }
    }

//    /**
//     * @return [int, \app\modules\Couber\models\CoubBigJson]
//     * @throws Exception
//     */
//    private function getSocialDataByUrl()
//    {
//        if ($this->social == self::SOCIAL_COUB) {
//            try {
//                $coubRresponse = $this->getSocialInfo();
//            } catch (Exception $e) {
//                Yii::error('getSocialDataByUrl - ['.$e->getCode().']'.$e->getMessage(), 'coub');
//                return array(null, null);
//            }
//            return array($coubRresponse->id, $coubRresponse);
//
//        } else {
//            throw new Exception('Error social type '.$this->social);
//        }
//    }

    /**
     * @return \app\modules\Couber\models\CoubBigJson
     * @throws Exception
     */
    public function getSocialInfo()
    {
        // todo save social data and use cache
        if ($this->social == self::SOCIAL_COUB) {
            if (empty($this->social_id)) {
                $social_id = $this->getCoubCode();
                if (!$social_id) {
                    throw new Exception('Error coub url');
                }
            }
            else
                $social_id = $this->social_id;
            $social_data = \app\modules\Couber\components\Coub::getCoub($social_id);
            if (empty($social_data)) {
                throw new Exception('Empty coub response');
            }
            elseif (isset($social_data->error)) {
                throw new Exception($social_data->error);
            }

            if ($this->isNewRecord)
                $this->_social_data = $social_data;
            else {
                if (!TaskSocialData::addTaskStats($this, $social_data)) {
                    throw new Exception('Error save task data');
                }
                $this->updateAttributes(['stats_time' => time()]);
            }
            return $social_data;
        } else {
            throw new Exception('Error social type');
        }
    }


    public function getFrameUrl()
    {
        if ($this->social == self::SOCIAL_COUB) {
            return 'https://coub.com/embed/' . $this->getCoubCode() .
            '?muted=false&autostart=true&originalSize=false&noSiteButtons=true&hideTopBar=true'; // &api2=true //&startWithHD=false // &noControls=true
        } else {
            throw new Exception('Error social type');
        }
    }

    /**
     * @param string $version
     * @return \app\models\TaskListRender
     * @throws Exception
     */
    public function getPreviewData($version = 'tiny')
    {
        $result = new \app\models\TaskListRender;
        if ($this->social == self::SOCIAL_COUB) {
            /* @var $social_data \app\modules\Couber\models\CoubBigJson */
            if (empty($this->tasker_info)) {
                try {
                    $social_data = $this->getSocialInfo();
                } catch (Exception $e) {
                    Yii::warning($e->getMessage(), 'coub');
                    if (!$this->setTaskBlock('Coub is unavailable')) {
                        throw new Exception('Error set block');
                    }
                    return $result;
                }
            } else {
                $social_data = json_decode($this->tasker_info->ti_info);
            }
//            print_r('<pre>');
//            print_r($social_data);
//            print_r('</pre>');
//            exit();
//            $social_data->src = 'https://coub.com/view/'.$social_data->permalink.'.gifv';
//            $social_data->src = self::parseCoubTemplate($social_data->image_versions, ['versions' => $version]);
            //$social_data->gif = $social_data->src; //$social_data->file_versions->email;
//            $social_data->mp4 = $social_data->file_versions->iphone->url;
//            $social_data->mp4_new = $social_data->file_versions->html5->video->med->url;
//            $social_data->flv = self::parseCoubTemplate($social_data->file_versions->web, ['types' => 'flv', 'versions' => 'small']);
            $result->id = $social_data->id;
            $result->title = $social_data->title;
            $result->img = self::parseCoubTemplate($social_data->image_versions, ['versions' => $version]);
            $result->duration = $social_data->duration;
//            $result->videoMp4 = $social_data->file_versions->html5->video->med->url;
            return $result;
        } else {
            throw new Exception('Error social type');
        }
    }

    public static function parseCoubTemplate($data, $replace) {
        $data = (array) $data;

        if (!isset($data['template'])) {
            return '';
        }
        $tpl = $data['template'];
        unset($data['template']);
        foreach($data as $k=>$r) {
            if (!isset($replace[$k])) {
                Yii::error('Undefined coub template '.$k);
            }
            else {
                $tpl = str_replace('%{'.substr($k, 0, -1).'}', $replace[$k], $tpl);
            }
        }
        return $tpl;
//        return str_replace('http://', '//', $tpl);
    }

    public function getStats()
    {
        $query = TaskSocialData::find();
        $query->select('td_date, td_stats')
            ->andFilterWhere(['=', 'td_tasker_id', $this->id])
            ->orderBy('td_id')
            ->limit(1000)
            ->asArray();
        return $query->all();
    }

    public function getGoodUrl($url)
    {
        $url = preg_replace(['/^http:\/\//i', '/^https:\/\//i', '/^www./i'], '', $url);
        return 'https://' . $url;
    }

    /*************** ***************/
    /*************** ***************/
    /************ COUB *************/
    /*************** ***************/
    /*************** ***************/

    public function getCoubCode()
    {
        $link = parse_url($this->social_link);
        $urlPathAr = preg_split('/\//', $link['path'], -1, PREG_SPLIT_NO_EMPTY);

        if ($urlPathAr[0] == 'view' && $urlPathAr[1]) {
            return $urlPathAr[1];
        }
        return null;
    }

    /********************************/

    public static function getActiveStatistic() {
        $i= 0;

//        $query = self::find();
        $query = self::getFreeLikeQuery();
        $query->andFilterWhere(['>', 'stats_mode', 0 ]);
        $query->andWhere('stats_time<('.time().'-(86400/stats_mode))');
        $query->limit(4);

        foreach ($query->all() as $task) {
            $social_data = null;
            try {
                /** @var self $task */
                $social_data = $task->getSocialInfo();
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'coub');
            }

            if (!$social_data) {
                echo ' *'.$task->getPrimaryKey();
                // просто ставим на паузу
                if (!$task->setTaskBlock('Coub is unavailable')) {
                    Yii::error('Error set block', 'coub');
                }
            }
            else {
                echo ' +'.$task->getPrimaryKey();
            }
            $i++;
        }
        return $i;
    }

    public static function getOffStatistic() {
        $i= 0;

        $query = self::find();
        $query
            ->andFilterWhere(['=', 'status', self::STATUS_BLOCK])
            ->andFilterWhere(['>', 'likes', 0]);
        $query->andFilterWhere(['>', 'stats_mode', 0 ]);
        $query->andWhere('stats_time<('.time().'-86400*5)');
        $query->limit(4);

        foreach ($query->all() as $task) {
            $social_data = null;
            try {
                /** @var self $task */
                $social_data = $task->getSocialInfo();
            } catch (Exception $e) {
                Yii::warning($e->getMessage(), 'coub');
            }

            if ($social_data) {
                echo ' +'.$task->getPrimaryKey();
                // включаем
                $task->status = self::STATUS_ON;
                $task->stats_time = time();
                $task->save(false, ['status', 'stats_time']);
                $i++;
            }
            else {
                echo ' -'.$task->getPrimaryKey();
                $task->stats_time = time();
                $task->save(false, ['stats_time']);
            }
        }
        return $i;
    }

    public static function setOffNotSupport() {
        $i= 0;

        $query = self::find();
        $query
            ->andFilterWhere(['=', 'status', self::STATUS_ON])
            ->andFilterWhere(['not in', 'type', array_keys(self::$_enumType) ])
            ->andFilterWhere(['>', 'likes', 0])
            ->limit(100);

        foreach ($query->all() as $task) {
            $res = false;
            try {
                /** @var self $task */
                $res = $task->setTaskBlock('Not support');
            } catch (Exception $e) {
                Yii::warning($e->getMessage(), 'coub');
            }

            if ($res) {
                echo ' +'.$task->getPrimaryKey();
                $i++;
            }
            else {
                echo ' -'.$task->getPrimaryKey();
            }
        }
        return $i;
    }

    public static function setOffInactive() {
        $i= 0;

        $query = self::find();
        $query
            ->andFilterWhere(['!=', 'status', self::STATUS_ON])
            ->andFilterWhere(['>', 'likes', 0])
            ->limit(100);

        foreach ($query->all() as $task) {
            $res = false;
            try {
                /** @var self $task */
                $res = $task->setTaskBlock('Auto compete');
            } catch (Exception $e) {
                Yii::warning($e->getMessage(), 'coub');
            }

            if ($res) {
                echo ' +'.$task->getPrimaryKey();
                $i++;
            }
            else {
                echo ' -'.$task->getPrimaryKey();
            }
        }
        return $i;
    }
}


class TaskListRender
{
    /**
     * Id
     * @var string
     */
    public $id;
    /**
     * Title
     * @var string
     */
    public $title;
    /**
     * Image
     * @var string
     */
    public $img;
    /**
     * duration
     * @var int
     */
    public $duration;
}