<?php

namespace app\models;

use Yii;
use yii\base\Exception;
/**
 * This is the model class for table "tasker_action".
 *
 * @property integer $tasker_id
 * @property string  $ti_date
 * @property string  $ti_info
 */
class TaskSocialInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasker_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tasker_id', 'ti_date', 'ti_info'], 'required'],
            [['tasker_id', 'ti_date', 'ti_info'], 'safe']
        ];
    }
    public function beforeValidate() {
        if ($this->ti_info && !is_string($this->ti_info)) {
            $this->ti_info = json_encode($this->ti_info);
        }

        $this->ti_date = date('Y-m-d H:i:s');
        return parent::beforeValidate();
    }


    /**
     * @param $task TaskSocial
     * @param $social_data
     * @return bool
     * @throws Exception
     */
    public static function addTaskInfo(TaskSocial $task, $social_data) {
        $info = self::find()->andFilterWhere(['=', 'tasker_id', $task->id])->one();
        if ($info) {
            return $info->updateAttributes(['ti_info' => json_encode($social_data), 'ti_date' => date('Y-m-d H:i:s')]);
        } else {
            $model = new self();
            $model->tasker_id = $task->id;
            $model->ti_info = $social_data;
            return $model->save();
        }
    }
}
