<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\UserPay\models\UserPay */

$this->title = $model->up_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'User Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-pay-view">

    <h1><?= Html::encode($this->title) ?></h1>
<!---->
<!--    <p>-->
<!--        --><?//= Html::a(Yii::t('admin', 'Update'), ['update', 'id' => $model->up_id], ['class' => 'btn btn-primary']) ?>
<!--        --><?//= Html::a(Yii::t('admin', 'Delete'), ['delete', 'id' => $model->up_id], [
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
            'up_id',
            'up_paysystem',
            'up_time_cr:datetime',
            'up_time_up:datetime',
            'up_amount',
            'up_user_id',
            'up_status',
            'up_likes',
            'up_likes_bonus',
            //'up_psid',
        ],
    ]) ?>

</div>
