<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\Admin\models\TaskSocial;
use \app\modules\Admin\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\models\TaskSocial */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('admin', 'Task Socials');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-social-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<!---->
<!--    <p>-->
<!--        --><?//= Html::a(Yii::t('admin', 'Create Task Social'), ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'time_cr:datetime',
            'time_up:datetime',
            'time_end:datetime',
            [
                'attribute'=>'type',
                'format'=>'text',
                'content'=>function($model) {
                    return $model->getNameType();
                },
                'filter' => TaskSocial::getEnumType()
            ],
//            'social',
            [
                'attribute'=>'status',
                'format'=>'html',
                'content'=>function($model) {
                    return '<span style="color:'.TaskSocial::$enumStatusColor[$model->status].';">'.TaskSocial::$enumStatus[$model->status].'</span>';
                },
                'filter' => TaskSocial::$enumStatus
            ],
            [
                'attribute'=>'user_id',
                'format'=>'text',
                'content'=>function($model) {
                    return ($model->user ? $model->user->username : ' - ').' ['.$model->user_id.']';
                },
                'filter' => User::getUserList()
            ],
//            'group_id',
            'social_id',
            'comment',
            'likes',
            'likes_spend',
            'likes_sum',
            'social_link',
//            'social_link_tiny',
            'action_sum',
            'action_cost',
            'reason',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>

</div>
