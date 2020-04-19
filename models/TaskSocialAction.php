<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tasker_action".
 *
 * @property integer $ta_id
 * @property integer $ta_tasker_id
 * @property integer $ta_user_id
 * @property integer $ta_time
 * @property integer $ta_status
 * @property string $ta_data
 */
class TaskSocialAction extends \yii\db\ActiveRecord
{
    const STATUS_BEGIN = 0;
    const STATUS_OK = 1;
    const STATUS_IGNORE = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasker_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ta_tasker_id', 'ta_user_id', 'ta_time', 'ta_status'], 'required'],
            [['ta_tasker_id', 'ta_user_id', 'ta_time', 'ta_status'], 'integer'],
            [['ta_data'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
//    public function attributeLabels()
//    {
//        return [
//            'ta_id' => Yii::t('app/model', 'ID'),
//            'ta_tasker_id' => Yii::t('app/model', 'ID задачи'),
//            'ta_user_id' => Yii::t('app/model', 'Пользователь'),
//            'ta_time' => Yii::t('app/model', 'Время создания'),
//            'ta_status' => Yii::t('app/model', 'Статус'),
//        ];
//    }

    public static function addTaskAction($tasker_id, $ta_data = '') {
        $model = new self();
        $model->ta_tasker_id = $tasker_id;
        $model->ta_time = time();
        $model->ta_data = substr($ta_data, 0, 255);
        $model->ta_status = self::STATUS_OK;
        $model->ta_user_id = Yii::$app->user->id;
        return $model->save();
    }

    public static function addTaskActionIgnore($tasker_id) {
        $model = new self();
        $model->ta_tasker_id = $tasker_id;
        $model->ta_time = time();
        $model->ta_status = self::STATUS_IGNORE;
        $model->ta_user_id = Yii::$app->user->id;
        return $model->save();
    }
}
