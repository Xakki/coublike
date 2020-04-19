<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TaskSocial */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Task Socials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-social-view">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    <p>-->
<!--        --><?//= Html::a(Yii::t('admin', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::a(Yii::t('admin', 'Delete'), ['delete', 'id' => $model->id], [
//            'class' => 'btn btn-danger',
//            'data' => [
//                'confirm' => Yii::t('admin', 'Are you sure you want to delete this item?'),
//                'method' => 'post',
//            ],
//        ]) ?>
<!--    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'time_cr:datetime',
            'time_up:datetime',
            'time_end:datetime',
            'type',
            'social',
            'status',
            'user_id',
            'group_id',
            'social_id',
            'comment',
            'likes',
            'likes_spend',
            'likes_sum',
            'social_link',
            'social_link_tiny',
            'action_sum',
            'action_cost',
            'reason',
        ],
    ]) ?>

</div>
