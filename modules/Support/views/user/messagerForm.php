<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;

/* @var $this yii\web\View */
/* @var $modelForm \app\modules\Support\models\Messages */
/* @var $messageList \app\modules\Support\models\Messages[] */
/* @var $messagePage \yii\data\Pagination */
/* @var $iam int */
/* @var $uid int */
/* @var $iamName string */
/* @var $uidName string */

if (!empty($modelForm)) {

    $form = ActiveForm::begin();

    ?>

    <?= $form->field($modelForm, 'mess')
        ->textarea(['placeholder' => Yii::t('support', (!$iam ? 'Write you answer' : 'Write you messages to support team')), 'maxlength' => 255])
        ->label(Yii::t('support', 'Message')) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('support', 'Send'), ['class' => 'btn btn-primary']) ?>
        <span class="label isNew"><?=Yii::t('support', 'New')?></span>
        <span class="label isNotRead"><?=Yii::t('support', 'Unread')?></span>
    </div>
    <?

    ActiveForm::end();
}
