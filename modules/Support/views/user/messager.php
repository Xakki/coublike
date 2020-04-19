<?php

use yii\widgets\LinkPager;
use \Yii;

/* @var $this yii\web\View */
/* @var $messageList \app\modules\Support\models\Messages[] */
/* @var $messagePage \yii\data\Pagination */
/* @var $iam int */
/* @var $uid int */
/* @var $iamName string */
/* @var $uidName string */
?>
    <ul class="chat">
    <? foreach ($messageList as $item): ?>
        <? if($item->user_from == $iam):?>
        <li class="right clearfix<?=($item->read ? '' : ' isNotRead')?>" data-id="<?=$item->id?>">
            <div class="chat-body clearfix">
                <div class="header">
                    <small class="text-muted"><span class="glyphicon glyphicon-time"></span><?=Yii::$app->formatter->asDatetime($item->created)?></small>
                    <strong class="primary-font"><?=Yii::t('support', 'You')?></strong>
                </div>
                <p><?=htmlentities($item->mess)?></p>
            </div>
        </li>
        <? else: ?>
        <li class="left clearfix<?=($item->read ? '' : ' isNew')?>" data-id="<?=$item->id?>">
            <div class="chat-body clearfix">
                <div class="header">
                    <strong class="primary-font"><?=htmlentities($uidName)?></strong>
                    <small class="text-muted"><span class="glyphicon glyphicon-time"></span><?=Yii::$app->formatter->asDatetime($item->created)?></small>
                </div>
                <p><?=htmlentities($item->mess)?></p>
            </div>
        </li>
        <? endif; ?>
    <? endforeach; ?>
    </ul>

    <?php
    echo LinkPager::widget([
        'pagination' => $messagePage,
        'registerLinkTags' => true,
        'maxButtonCount' => 6
    ]);
    ?>

<script>
        onLoadArr.push(function () {
            setTimeout(function() {
                var newId = [];
                $('li.isNew').each(function() {
                    newId.push($(this).attr('data-id') );
                    $(this).removeClass('isNew');
                });
                if (newId.length) {
                    $.ajax({
                        type: "POST",
                        data: {newIds : newId},
                        dataType: 'json',
                        success: function() {

                        }
                    });
                }
            }, 3000);
        });
</script>
