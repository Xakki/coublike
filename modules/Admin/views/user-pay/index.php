<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\Admin\models\UserPay;
use \app\modules\Admin\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\models\UserPay */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'User Pays');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-pay-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>-->
<!--        --><?//= Html::a(Yii::t('admin', 'Create User Pay'), ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'up_id',
            'up_paysystem',
            'up_time_cr:datetime',
            'up_time_up:datetime',
            'up_amount',
            [
                'attribute'=>'up_user_id',
                'format'=>'text',
//                'contentOptions' =>function ($model, $key, $index, $column){
//                    return ['class' => 'name'];
//                },
                'content'=>function($model) {
                    return ($model->user ? $model->user->username : ' - ').' ['.$model->up_user_id.']';
                },
                'filter' => User::getUserList()
            ],
            [
                'attribute'=>'up_status',
                'format'=>'html',
                'content'=>function($model) {
                    return '<span style="color:'.UserPay::$status_color[$model->up_status].';">'.UserPay::$status_enum[$model->up_status].'</span>';
                },
                'filter' => UserPay::$status_enum
            ],
            'up_likes',
            'up_likes_bonus',
//            'up_psid',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>

</div>
