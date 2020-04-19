<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\TaskSocial */
/* @var $form ActiveForm */
if($model->id) {
    $this->title = Yii::t('app', 'Edit task');
}
else {
    $this->title = Yii::t('app', 'New task');
}
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs("");

$req = ' <i style="color:#b24337;">*</i>';

?>
<div class="dushboard-addTask center-block-400">

    <?
    $btnProperty = ['class' => 'btn btn-primary'];
    if ( !$model->canUserAddTask() ):
        $btnProperty['disabled'] = 'disabled';
        echo Alert::widget([
            'options' => [
                'class' => 'alert-danger'
            ],
            'body' => Yii::t('app', 'To create a task, you must have the account').' '.$model->minUserLikeForTask().' '.Yii::t('app', 'likes')
        ]);
    endif; ?>

    <?php
        $form = ActiveForm::begin([
            'options' => ['class' => 'type-'.$model->type],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class' => 'help-block'
                ],
            ],
        ]);
        $optCost = '';
        foreach($model->optimalCost() as $ktype=>$rVal):
            $optCost .= '<i class="optimal-cost t-'.$ktype.'">'.$rVal.'</i>';
        endforeach;
        $actionNames = '';
        foreach($model->getEnumTypes() as $k=>$r):
            $actionNames .= '<i class="optimal-cost t-'.$k.'">'.$r.'</i>';
        endforeach;
    ?>

    <? if ($model->isNewRecord): ?>
        <?= $form->field($model, 'type')
            ->inline(true)
            ->radioList($model::getEnumType(), [
                'class' => 'btn-group btn-group-justified',
                'data-toggle' => 'buttons',
                'itemOptions' => [
                    'labelOptions' => ['class' => 'btn btn-default'],
                    'autocomplete' => 'off',
                ]
            ])
            ->label(Yii::t('app', 'Type').$req) ?>
    <? else: ?>
        <h3><?=Yii::t('app', $model->getNameType())?></h3>
    <? endif; ?>

    <?= $form->field($model, 'social_link')
        ->textInput(['placeholder' => Yii::t('app', 'Url example:').' https://coub.com/view/1acvgb'])
        ->label(Yii::t('app', 'Link').$req) ?>

    <?= $form->field($model, 'action_cost')
        ->input('number', [
            'placeholder' => Yii::t('app', 'Minimum').' '.$model::getMinCost($model),
            'min' => $model::getMinCost($model),
            'data-min' => Yii::t('app', 'Minimum')
        ])
        ->label(Yii::t('app', 'Cost').' ('.Yii::t('app', 'optimally').' '.$optCost.') '.$req) ?>

    <?= $form->field($model, 'action')
        ->input('number', [
            'placeholder' => Yii::t('app', 'Minimum').' '.$model::LIMIT_MIN_ACTIONS,
            'min' => $model::LIMIT_MIN_ACTIONS
        ])
        ->label(Yii::t('app', 'How many need {0}?', [$actionNames]) .$req) ?>

    <?= $form->field($model, 'likes')
        ->input('text', [
            'disabled' => 'disabled',
            'placeholder' => Yii::t('app', 'Available').' '.Yii::$app->user->identity->getAttribute('likes').' '.Yii::t('app', 'likes'),
        ])
        ->label(Yii::t('app', 'You spend')) ?>


    <?= $form->field($model, 'comment')
        ->textarea(['placeholder' => Yii::t('app', 'Name for you')])
        ->label(Yii::t('app', 'Name')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save and run task'), $btnProperty) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>

<script>
    taskData = {
        id : <?=(int)$model->id?>,
        oldLikes : <?=(int)$model->getOldAttribute('likes')?>,
        minCost: <?=json_encode($model->getDataMinCost())?>,
        social: "<?=$model->social?>"
    };
    userData = {
        likes : <?=Yii::$app->user->identity->getAttribute('likes')?>
    };
    onLoadArr.push(function() {
        $('#tasksocial-action_cost, #tasksocial-action').on('keyup change', actionCostChange);
        if (taskData.id) {
            $('#tasksocial-action_cost, #tasksocial-action').change();
        }
        else {
            $('#tasksocial-type input').on('change', actionTypeChange);
            $('#tasksocial-type label:first').addClass('active');
        }
//        $('#tasksocial-type').hide();
//        $(".btn-group button").click(function () {
//            $("#buttonvalue").val($(this).text());
//        });
        $('.type-<?=$model->type?>').submit(function( e ){
            $('button[type=submit]', $(this)).prop( 'disabled', true );
            // e.preventDefault();
            return true;
        });
        $('.dushboard-addTask form input').on('change', function(){
            $('.dushboard-addTask form button[type=submit]').prop('disabled', false);
            // e.preventDefault();
            return true;
        });
    });
</script>
