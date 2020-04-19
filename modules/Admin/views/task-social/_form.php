<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TaskSocial */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-social-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'time_cr')->textInput() ?>

    <?= $form->field($model, 'time_up')->textInput() ?>

    <?= $form->field($model, 'time_end')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList(\app\models\TaskSocial::getTypes(), ['prompt' => '']) ?>

    <?= $form->field($model, 'social')->dropDownList([ 'tw' => 'Tw', 'coub' => 'Coub', 'fb' => 'Fb', 'vk' => 'Vk', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'group_id')->textInput() ?>

    <?= $form->field($model, 'social_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'likes')->textInput() ?>

    <?= $form->field($model, 'likes_spend')->textInput() ?>

    <?= $form->field($model, 'likes_sum')->textInput() ?>

    <?= $form->field($model, 'social_link')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'social_link_tiny')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action_sum')->textInput() ?>

    <?= $form->field($model, 'action_cost')->textInput() ?>

    <?= $form->field($model, 'reason')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('admin', 'Create') : Yii::t('admin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
