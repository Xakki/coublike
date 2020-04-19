<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\UserPay\models\UserPay */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-pay-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'up_id')->textInput() ?>

    <?= $form->field($model, 'up_paysystem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'up_time_cr')->textInput() ?>

    <?= $form->field($model, 'up_time_up')->textInput() ?>

    <?= $form->field($model, 'up_amount')->textInput() ?>

    <?= $form->field($model, 'up_user_id')->textInput() ?>

    <?= $form->field($model, 'up_status')->textInput() ?>

    <?= $form->field($model, 'up_likes')->textInput() ?>

    <?= $form->field($model, 'up_likes_bonus')->textInput() ?>

    <?/*= $form->field($model, 'up_psid')->textInput(['maxlength' => true]) */?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
