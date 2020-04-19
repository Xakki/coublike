<?php

/* @var $this yii\web\View */
/* @var $modelForm \app\modules\Support\models\Messages */
/* @var $messageList \app\modules\Support\models\Messages[] */
/* @var $messagePage \yii\data\Pagination */
/* @var $iam int */

$this->title = Yii::t('support', 'Support');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(\app\modules\Support\Module::getInstance()->getAssetsUrl().'/support.css');
?>


<? if(LOC_IS_RU):?>
    <p> По всем вопросам обращаться на почту <a href="mailto:support@<?=HOST?>">support@<?=HOST?></a></p>
<? else:?>
    <p> On all questions to address on mail <a href="mailto:support@<?=HOST?>">support@<?=HOST?></a></p>
<? endif;?>
<div class="center-block-400">

    <?=$this->render('messagerForm', $_params_)?>

</div>

<div class="center-block-400">
    <?=$this->render('messager', $_params_)?>
</div>
