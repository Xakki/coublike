<?php

namespace app\models;

use Yii;
use yii\base\Exception;
/**
 * This is the model class for table "tasker_action".
 *
 * @property integer $td_id
 * @property integer $td_tasker_id
 * @property string  $td_date
 * @property string  $td_stats
 */
class TaskSocialData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasker_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['td_tasker_id', 'td_date'], 'required'],
            [['td_tasker_id', 'td_date', 'td_stats'], 'safe']
        ];
    }
    public function beforeValidate() {

        if ($this->td_stats && !is_string($this->td_stats)) {
            $this->td_stats = json_encode($this->td_stats);
        }
        $this->td_date = date('Y-m-d H:i:s');
        return parent::beforeValidate();
    }


    /**
     * @param $task TaskSocial
     * @param $social_data
     * @return bool
     * @throws Exception
     */
    public static function addTaskStats(TaskSocial $task, $social_data) {
        if (self::find()
            ->andFilterWhere(['=', 'td_tasker_id', $task->id])
            ->andFilterWhere(['>', 'td_date', date('Y-m-d H:i:s', (time()-3600))])
            ->count()) return true;
        $model = new self();
        $model->td_tasker_id = $task->id;

        if ($task->social == $task::SOCIAL_COUB) {
            $model->td_stats = [
                'view' => $social_data->views_count,
                'repost' => $social_data->recoubs_count,
                'like' => $social_data->likes_count,
                'follow' => $social_data->channel->followers_count,
//                'recoub' => $social_data->recoub,
//                'like' => $social_data->like,
                'ban' => $social_data->banned,
//                'myb15' => $social_data->in_my_best2015,
//                'bb14' => $social_data->beeline_best_2014,
//                'b15' => $social_data->best2015_addable,
//                'pw' => $social_data->promo_winner,
//                'pwc' => $social_data->promo_winner_recoubers,
//                'ph' => $social_data->promo_hint,
//                'vic' => $social_data->views_increase_count,
//                'sc' => $social_data->shares_count,
//                'rbuc' => $social_data->recoubs_by_users_channels,
//                'cfsc' => $social_data->channel->followers_count,
//                'cfgc' => $social_data->channel->following_count,
//                'ccc' => $social_data->channel->coubs_count,
//                'crc' => $social_data->channel->recoubs_count,
//                'tvd' => $social_data->total_views_duration,
            ];
        }

        $res = $model->save();
        if ($res) {
            \app\models\TaskSocialInfo::addTaskInfo($task, $social_data);
        }

        return $res;
    }
}
