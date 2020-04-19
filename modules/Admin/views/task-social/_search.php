<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Admin\models\TaskSocial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-social-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'time_cr') ?>

    <?= $form->field($model, 'time_up') ?>

    <?= $form->field($model, 'time_end') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'social') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php // echo $form->field($model, 'group_id') ?>

    <?php // echo $form->field($model, 'social_id') ?>

    <?php // echo $form->field($model, 'comment') ?>

    <?php // echo $form->field($model, 'likes') ?>

    <?php // echo $form->field($model, 'likes_spend') ?>

    <?php // echo $form->field($model, 'likes_sum') ?>

    <?php // echo $form->field($model, 'social_link') ?>

    <?php // echo $form->field($model, 'social_link_tiny') ?>

    <?php // echo $form->field($model, 'action_sum') ?>

    <?php // echo $form->field($model, 'action_cost') ?>

    <?php // echo $form->field($model, 'reason') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('admin', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('admin', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
