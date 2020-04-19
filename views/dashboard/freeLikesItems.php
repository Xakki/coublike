<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $data \app\models\TaskSocial[] */
/* @var $totalCount int */
/* @var $pageSize int */
/* @var $social_data \app\models\TaskListRender */

?>

<? foreach ($data as $i): ?>
    <? $social_data = $i->getPreviewData('small'); ?>
    <div class="col-block isNew social_<?=$i->social?> type_<?=$i->type?>" data-duration="<?=ceil($social_data->duration)?>" data-id="<?=$i->id?>">

        <div class="frame_block" data-iframe="<?=$i->getFrameUrl()?>">
            <img class="frame_block_preview" src="<?= $social_data->img ?>" alt="<?= $social_data->title ?>"/>
            <i class="animateSuccessLike">+<?= $i->action_cost ?></i>
            <i class="playBtnPreview"></i>
            <i class="actionBtnPreview">
                <a data-url="<?= Url::to(['dashboard/set-complete-task', 'id' => $i->id]) ?>" class="getFreeLike" data-cost="<?= $i->action_cost ?>">+<?= $i->action_cost ?>
                    <span class="glyphicon glyphicon-eye-open ico_view" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-heart ico_like" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-retweet ico_repost" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-user ico_follow" aria-hidden="true"></span>
                </a>
                <span class="actionBtnPreviewInfo"><?=Yii::t('app', 'Click, to get reward!') ?></span>
            </i>
        </div>
        <div class="col-block-btns">
            <a data-url="<?= Url::to(['dashboard/set-complete-task', 'id' => $i->id]) ?>" class="getFreeLike" data-cost="<?= $i->action_cost ?>">+<?= $i->action_cost ?>
                <span class="glyphicon glyphicon-eye-open ico_view" aria-hidden="true"></span>
                <span class="glyphicon glyphicon-heart ico_like" aria-hidden="true"></span>
                <span class="glyphicon glyphicon-retweet ico_repost" aria-hidden="true"></span>
                <span class="glyphicon glyphicon-user ico_follow" aria-hidden="true"></span>
            </a>
            <a data-url="<?= Url::to(['dashboard/set-ignore', 'id' => $i->id]) ?>" class="ignoreLike" title="<?= Yii::t('app', 'Ignore') ?>"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span></a>
            <span class="glyphicon glyphicon-ok successTask" aria-hidden="true"></span>
        </div>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <script>idList.push(<?=$i->id?>);</script>
    </div>
<? endforeach; ?>

<? if ($totalCount>count($data) && $totalCount > $pageSize *2 ) : ?>
    <div class="showMorePages"><button class="btn btn-default">Показать еще <?=$pageSize?> из <?=($totalCount - $pageSize)?></button></div>
<? elseif ($totalCount>count($data)) : ?>
    <div class="showMorePages"><button class="btn btn-default">Показать еще <?=($totalCount - $pageSize)?></button></div>
<? endif; ?>


