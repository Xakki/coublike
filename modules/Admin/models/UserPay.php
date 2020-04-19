<?php

namespace app\modules\Admin\models;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\UserPay\models\UserPay as UserPayModel;
use yii\helpers\ArrayHelper;

/**
 * UserPay represents the model behind the search form about `\app\modules\UserPay\models\UserPay`.
 */
class UserPay extends UserPayModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['up_id', 'up_time_cr', 'up_time_up', 'up_user_id', 'up_status', 'up_likes', 'up_likes_bonus'], 'integer'],
            [['up_paysystem'/*, 'up_psid'*/], 'safe'],
            [['up_amount'], 'number'],
        ];
    }

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
        $query = UserPayModel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['up_id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'up_id' => $this->up_id,
            'up_time_cr' => $this->up_time_cr,
            'up_time_up' => $this->up_time_up,
            'up_amount' => $this->up_amount,
            'up_user_id' => $this->up_user_id,
            'up_status' => $this->up_status,
            'up_likes' => $this->up_likes,
            'up_likes_bonus' => $this->up_likes_bonus,
        ]);

        $query->andFilterWhere(['like', 'up_paysystem', $this->up_paysystem]);
//            ->andFilterWhere(['like', 'up_psid', $this->up_psid]);

        return $dataProvider;
    }

    public static $status_color = [
        self::STATUS_INIT => 'gray',
        self::STATUS_ERROR => 'red',
        self::STATUS_OK => 'green',
    ];

}
