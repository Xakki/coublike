<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Admin\models\UserPay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-pay-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'up_id') ?>

    <?= $form->field($model, 'up_paysystem') ?>

    <?= $form->field($model, 'up_time_cr') ?>

    <?= $form->field($model, 'up_time_up') ?>

    <?= $form->field($model, 'up_amount') ?>

    <?//=$form->field($model, 'up_user_id') ?>

    <?//=$form->field($model, 'up_status') ?>

    <?//=$form->field($model, 'up_likes') ?>

    <?//=$form->field($model, 'up_likes_bonus') ?>

    <?//=$form->field($model, 'up_psid') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('admin', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
