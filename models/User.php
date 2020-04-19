<?php

namespace app\models;

use dektrium\user\models\User as BaseUser;
use Yii;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Json;

/**
 * Class User
 * @package app\models
 * @see https://github.com/dektrium/yii2-user/blob/master/docs/README.md
 * @property integer $login_at
 * @property integer $likes
 * @property integer $likes_sum
 * @property integer $likes_pay_sum
 * @property integer $likes_earn_sum
 * @property integer $likes_buy_sum
 * @property integer $referral_id
 * @property integer $referral_url
 * @property integer $referral_earn     Накопительная, для ежесуточных выплат
 * @property integer $referral_buy      Накопительная, для ежесуточных выплат
 * @property integer $likes_ref_sum     Накопительная, для ежесуточных выплат
 * @property User $owner
 */
class User extends BaseUser
{
    /**
     * @var \app\modules\Couber\authclients\CoubAuth
     */
    private $_coubAuth = null;
    private $_account = null;
    private $_accountData = null;

//
//    public function init()
//    {
//        parent::init();
//        $this->mailer = Yii::$container->get(Mailer::class);
//        Yii::$app->user->on(\yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
//        });
//    }

    /** @inheritdoc */
    public static function findIdentity($id)
    {
        $identity = parent::findIdentity($id);
        if ($identity) {
            $identity->updateLastLogin();
        }
        return $identity;
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'register' => ['email', 'password'],
            'connect' => ['email'],
            'create' => ['email', 'password'],
            'update' => ['email', 'password'],
            'settings' => ['email', 'password'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        $rules = parent::rules();
        unset($rules['usernameRequired']);
        unset($rules['emailRequired']);
//        $rules['referral_url'] = ['referral_url', 'safe', 'on' => ['register', 'connect', 'create']];
//        $rules['referral_id'] = ['referral_id', 'safe', 'on' => ['register', 'connect', 'create']];
        return $rules;
    }


    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('likes', Yii::$app->params['freeLikes']);
            $this->setAttribute('likes_sum', Yii::$app->params['freeLikes']);
            if (\Yii::$app->request->cookies->has('refid')) {
                $this->setAttribute('referral_id', (int) \Yii::$app->request->cookies->getValue('refid'));
            }
            if (Yii::$app->request->cookies->has('refurl')) {
                $this->setAttribute('referral_url', substr(\Yii::$app->request->cookies->getValue('refurl'), 0, 255));
            }
        }

        return parent::beforeSave($insert);
    }

    public function updateLastLogin()
    {
        $this->everyDayBonus();
        // every 1 min
        if (!$this->login_at || $this->login_at < (time() - 60) ) {
            $this->updateAttributes(['login_at' => time()]);
            Yii::info("User ".$this->id." : Update last login time");
        }
        return true;
    }

    public function everyDayBonus()
    {
        if ($this->isNewDayVisit() && Yii::$app->id !== 'console') {
            $this->addVisitLikes();
            Yii::info("User ".$this->id." : Add free likes");
            Yii::$app->session->setFlash(FLASH_OK, Yii::t('app', 'For the daily visits you get {0} likes', [Yii::$app->params['freeLikes']]));
        }
        return true;
    }

    public function isNewDayVisit() {
        return (!$this->login_at || time() > ($this->login_at + 24*3600) || date('z',$this->login_at)<date('z'));
    }

    /**
     * RELATION to owner referral
     * @return $this
     */
    public function getOwner()
    {
        return $this->hasOne(self::class, ['id' => 'referral_id']);
    }

    /**
     * Only for create task
     * @param $likes
     * @return bool
     * @throws Exception
     */
    public function reserveLikes($likes)
    {
        if ($likes == 0) {
            return true;
        }

        // if only dicrase balance
        if ($likes > 0 && ($this->likes - $likes) < 0) {
            throw new Exception('Limit exceeded');
        }

        $this->likes = $this->likes - $likes;
        $this->likes_pay_sum = $this->likes_pay_sum + $likes;

        return $this->save(false, ['likes', 'likes_pay_sum']);
    }


    public function getAccounts($forse = false)
    {
        if (is_null($this->_account) || $forse) {
            $this->_account = parent::getAccounts();
        }
        return $this->_account;
    }

    /**
     * Get OpenAuth token ?need for API
     * @param $account
     * @return string|null
     */
    public function getTokenByAccount($account)
    {

        if (!isset($this->accounts[$account])) {
            return null;
        }
        return $this->accounts[$account]->token;
    }

    /**
     * Check enable social convection
     * @return bool
     */
    public function hasAccount($provider)
    {
        return (isset($this->accounts[$provider]));
    }

    /**
     * @return \app\modules\Couber\authclients\CoubAuth
     */
    public function getCoubApi()
    {
        if (!isset($this->_coubAuth)) {
//            $token = \Yii::$app->user->identity->getTokenByAccount('coub');
//            $coubApi = \Yii::$app->user->identity->accounts['coub'];
//            $coubApi->setAccessToken(\Yii::$app->user->identity->getTokenByAccount('coub'));
            try {
                $this->_coubAuth = new \app\modules\Couber\authclients\CoubAuth;
                $t = $this->_coubAuth->getAccessToken();
                if (!$t) {
                    // update token
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        }
        return $this->_coubAuth;
    }

    /**
     * earn likes
     * @param $likes
     * @param $logData
     * @return bool
     * @throws Exception
     */
    public function addLikes($likes, $logData)
    {
        $data = ['likes' => ($this->likes + $likes), 'updated_at' => time(),
            'likes_earn_sum' => ($this->likes_earn_sum + $likes), 'likes_sum' => ($this->likes_sum + $likes)];

        if (!$this->updateAttributes($data)) {
            throw new Exception('Error update likes');
        }

        UserLog::addLog(UserLog::TYPE_EARN_LIKES, $this->id, $likes, $logData);
        return true;
    }

    public function addVisitLikes()
    {
        $likes = Yii::$app->params['freeLikes'];
        $data = ['likes' => ($this->likes + $likes), 'updated_at' => time(), 'likes_earn_sum' => ($this->likes_earn_sum + $likes), 'likes_sum' => ($this->likes_sum + $likes)];
        $this->updateAttributes($data);
        UserLog::addLog(UserLog::TYPE_EVERY_DAY, $this->id, $likes);
        return true;
    }

    /**
     * buy likes
     * @param $likes
     * @return bool
     * @throws Exception
     */
    public function buyLikes($likes, $bonus, $logData)
    {
        $data = ['likes' => ($this->likes + $likes + $bonus), 'updated_at' => time(),
            'likes_buy_sum' => ($this->likes_buy_sum + $likes + $bonus), 'likes_sum' => ($this->likes_sum + $likes + $bonus)];

        if (!$this->updateAttributes($data)) {
            throw new Exception('Error update likes');
        }
        UserLog::addLog(UserLog::TYPE_BUY_LIKES, $this->id, $likes, $logData);
        if ( $bonus )
            UserLog::addLog(UserLog::TYPE_BUY_LIKES_BONUS, $this->id, $bonus, $logData);
        return true;
    }

//    public static $usernameRegexp = '/^[a-zA-Z0-9_\.@]+$/';

    public static function getNewUsername($name)
    {
        $user = new self;
        $user->setScenario('connect');
        if (!$name) {
            $name = 'User';
        } else {
            $name = preg_replace('/[^a-zA-Z0-9_\.@]+/', '_', $name);
        }
        $user->username = $name;

        // generate username like "user1", "user2", etc...
        while (!$user->validate(['username'])) {
            $row = (new Query())
                ->from('{{%user}}')
                ->select('MAX(id) as id')
                ->one();
            if (empty($row['id'])) {
                throw new Exception('Error get user name: '.$name);
            }
            $user->username = $name . ++$row['id'];
        }

        return $user->username;
    }

    /**
     * @return null|\app\modules\Couber\models\User
     * @throws \app\exception\ExceptionReConnect
     */
    public function getAccountData() {
        if ($this->hasAccount('coub')) {
            try {
                $this->_accountData = Json::decode($this->accounts['coub']->data, false);
            } catch (\Throwable $e) {
                Yii::error($e, 'user');
            }
            if (empty($this->_accountData) || !isset($this->_accountData->current_channel)) {
                $this->accounts['coub']->deleteInternal();
                throw new \app\exception\ExceptionReConnect('User need update account data : '.$this->getId());
            }
            return $this->_accountData;
        }
        return null;
    }

    public function getChannelTitle()
    {
        if ($this->hasAccount('coub')) {
            return $this->getAccountData()->current_channel->title;
        }
        return '-';
    }

    public function getChannelLink()
    {
        if ($this->hasAccount('coub')) {
            return 'https://coub.com/'.$this->getAccountData()->current_channel->permalink;
        }
        return '-';
    }

    public function getChannelsId()
    {
        if ($this->hasAccount('coub')) {
            $ids = [];
            foreach($this->getAccountData()->channels as $channels) {
                $ids[$channels->id] = $channels->id;
            }
            return $ids;
        }
        return [];
    }

    public function getAvatar($version = 'main')
    {
        if ($this->hasAccount('coub')) {
            $versionList = [
                'main' => 'profile_pic'
                // "medium","medium_2x","profile_pic","profile_pic_new","profile_pic_new_2x","tiny","tiny_2x","small","small_2x","ios_large","ios_small"
            ];
            if (!isset($versionList[$version])) {
                $version = 'main';
            }
            $version = $versionList[$version];

            $data = $this->getAccountData();

            if (isset($data->current_channel->avatar_versions->template)) {
                $template = $data->current_channel->avatar_versions->template;
                return str_replace(['%{version}'], [$version], $template);//, 'http://' // , '//'
            }
        }
        return '/img/avatar.svg';

    }

    public function referralTaxCommit()
    {
        $i = 0;
        if ($this->referral_id && $this->owner) {
            $likesEarnRefByType = $this->getUserLogsByLikes();

            if (count($likesEarnRefByType)) {
                $likesEarn = $likesBuy = 0;
                foreach ($likesEarnRefByType as $likesRef) {
                    if ($likesRef['ul_type'] == UserLog::TYPE_EARN_LIKES) {
                        $likesEarn += $likesRef['s'];
                        $i++;
                    } elseif ($likesRef['ul_type'] == UserLog::TYPE_BUY_LIKES) {
                        $likesBuy += $likesRef['s'];
                        $i++;
                    }
                }
                if ($likesEarn) {
                    $likesEarnPr = ceil($likesEarn * Yii::$app->params['earnLikeGift'] / 100);
                    echo ' * $likesEarn='.$likesEarn.' / '.$likesEarnPr.PHP_EOL;
                    if ($likesEarnPr > 0 and $this->owner->addLikeByRef($likesEarnPr)) {
                        UserLog::addLog(UserLog::TYPE_REF_EARN, $this->owner->id, $likesEarnPr, $this->id, $likesEarn);
                        $this->updateAttributes(['referral_earn' => ($this->referral_earn + $likesEarnPr)]);
                    }
                }
                if ($likesBuy) {
                    $likesBuyPr = ceil($likesBuy * Yii::$app->params['earnBuyGift'] / 100);
                    echo ' * $likesBuy='.$likesBuy.' / '.$likesBuyPr.PHP_EOL;
                    if ($likesBuyPr > 0 and $this->owner->addLikeByRef($likesBuyPr)) {
                        UserLog::addLog(UserLog::TYPE_REF_BUY, $this->owner->id, $likesBuyPr, $this->id, $likesBuy);
                        $this->updateAttributes(['referral_buy' => ($this->referral_earn + $likesBuyPr)]);
                    }
                }
                $this->setUserLogsByLikesComplete();
                return $i;
            }

        }
        return $i;
    }

    public function addLikeByRef($likes)
    {
        return $this->updateAttributes([
            'likes' => ($this->likes + $likes),
            'likes_ref_sum' => ($this->likes_ref_sum + $likes),
            'likes_sum' => ($this->likes_sum + $likes)]);
    }

    /**
     * @return array
     */
    private function getUserLogsByLikes()
    {
        $query = UserLog::find();
        $query->select(['ul_type', 'sum(ul_int) as s']);
        $query->where(['ul_user_id' => $this->id, 'ul_type' => [UserLog::TYPE_EARN_LIKES, UserLog::TYPE_BUY_LIKES], 'ul_flag' => 0]);
        $query->groupBy(['ul_type']);
        return $query->asArray()->all();
    }

    private function setUserLogsByLikesComplete()
    {
        UserLog::updateAll(['ul_flag' => 1], ['ul_user_id' => $this->id, 'ul_type' => [UserLog::TYPE_EARN_LIKES, UserLog::TYPE_BUY_LIKES], 'ul_flag' => 0]);
    }
//
//    public function referralPayCommit()
//    {
//        if ($this->referral_buy > 0) {
//            $likes = ($this->referral_buy * Yii::$app->referrals->buylike / 100);
//            if ($this->owner) {
//                if ($this->owner->updateAttributes(['likes' => ($this->owner->likes + $likes), 'likes_ref_sum' => ($this->owner->likes_ref_sum + $likes), 'likes_sum' => ($this->owner->likes_sum + $likes)])) {
//                    UserLog::addLog(UserLog::TYPE_REF_PAY, $this->owner->id, $likes, $this->id);
//                    $this->updateAttributes(['referral_buy' => 0]);
//                }
//            }
//        }
//    }

    public function getIsAdmin()
    {
        return !empty($this->module->admins[$this->id]);
    }
}