<?php

namespace app\modules\Admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TaskSocial as TaskSocialModel;

/**
 * TaskSocial represents the model behind the search form about `\app\models\TaskSocial`.
 */
class TaskSocial extends TaskSocialModel
{


    public static $enumStatus= array(
        self::STATUS_PAUSE => 'Pause',
        self::STATUS_ON => 'Run',
        self::STATUS_COMPLETE => 'Complete',
        self::STATUS_DEL => 'Delete',
        self::STATUS_BLOCK => 'Blocked',
    );

    public static $enumStatusColor = [
        self::STATUS_PAUSE => 'gray',
        self::STATUS_ON => 'black',
        self::STATUS_COMPLETE => 'green',
        self::STATUS_DEL => 'blue',
        self::STATUS_BLOCK => 'red',
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'time_cr', 'time_up', 'time_end', 'status', 'user_id', 'group_id', 'social_id', 'likes', 'likes_spend', 'likes_sum', 'action_sum', 'action_cost'], 'integer'],
            [['type', 'social', 'comment', 'social_link', 'social_link_tiny', 'reason'], 'safe'],
        ];
    }

//    public function attributeLabels()
//    {
//        return [
//            'id'          => 'Id',
//            'time_cr'             => Yii::t('user', 'Email'),
//            'time_up'   => Yii::t('user', 'Registration ip'),
//            'unconfirmed_email' => Yii::t('user', 'New email'),
//            'password'          => Yii::t('user', 'Password'),
//            'created_at'        => Yii::t('user', 'Registration time'),
//            'confirmed_at'      => Yii::t('user', 'Confirmation time'),
//        ];
//    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TaskSocialModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'time_cr' => $this->time_cr,
            'time_up' => $this->time_up,
            'time_end' => $this->time_end,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'group_id' => $this->group_id,
            'social_id' => $this->social_id,
            'likes' => $this->likes,
            'likes_spend' => $this->likes_spend,
            'likes_sum' => $this->likes_sum,
            'action_sum' => $this->action_sum,
            'action_cost' => $this->action_cost,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'social', $this->social])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'social_link', $this->social_link])
//            ->andFilterWhere(['like', 'social_link_tiny', $this->social_link_tiny])
            ->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
