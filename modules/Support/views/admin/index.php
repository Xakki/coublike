<?php
/* @var $this yii\web\View */
/* @var $dialogList array */
/* @var $dialogTotalCount int */
/* @var $dialogPageSize int */
/* @var $uid int */
/* @var $iam int */
/* @var $messageList models\Messages[] */
/* @var $messagePage \yii\data\Pagination */

$this->title = Yii::t('support', 'Support');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(\app\modules\Support\Module::getInstance()->getAssetsUrl().'/support.css');

?>
<table class="dialogTable">
    <tr>
        <td class="dialogUsers">
            <? if($dialogTotalCount): ?>
                <div class="list-group">
                <? foreach($dialogList as $item): ?>
                    <a class="list-group-item user<?=$item['uid']?><?=($item['new'] ? ' hasNew' : '')?><?=(!empty($uid) && $item['uid']==$uid ? ' active' : '')?>" href="?uid=<?=$item['uid']?>">
                        <span class="userName"><?=$item['user']['username']?></span>
                        <span class="badge badge-light countMess"><?=$item['cnt']?></span>
                        <span class="badge badge-info newMess"><?=$item['new']?></span>
                        <span class="lastTime"><?=Yii::$app->formatter->asDatetime($item['crt'])?></span>
                    </a>
                <? endforeach; ?>
                </div>
            <? else: ?>
                <span><?=Yii::t('app', 'Empty')?></span>
            <? endif; ?>
        </td>

        <td class="dialogMessages">
            <? if(!empty($messageList) && count($messageList)): ?>
                <?=$this->render('../user/messagerForm', $_params_)?>
                <?=$this->render('../user/messager', $_params_)?>
            <? else: ?>
                <span><?=Yii::t('app', 'Empty')?></span>
            <? endif; ?>
        </td>

    </tr>
</table>
