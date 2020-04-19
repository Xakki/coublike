<?php
use yii\widgets\LinkPager;
use yii\bootstrap\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $data \app\models\TaskSocial[] */
/* @var $pages \yii\data\Pagination */
/* @var $social_data \app\models\TaskListRender */
$statusColor = [
    0 => '',
    1 => 'success',
    2 => 'info',
    4 => 'warning',
    5 => 'danger',
];
if (!empty($pages)):
    $this->title = Yii::t('app', 'My page');
    $this->params['breadcrumbs'][] = $this->title. ($pages->totalCount ? ' ['. $pages->totalCount .']' : '');
endif;
$l_likes = \Yii::t('app', 'likes');
$l_from = \Yii::t('app', 'из');
$l_done= \Yii::t('app', 'Done');
$l_amount = \Yii::t('app', 'Amount');
$l_costForAction = \Yii::t('app', 'Cost for action');
$l_stats = Yii::t('app', 'Stats');
$l_info = Yii::t('app', 'Info');
$l_pause = Yii::t('app', 'Pause');
$l_delete = Yii::t('app', 'Delete');
$l_complete = Yii::t('app', 'Complete');
$l_confirm_delete = Yii::t('app', 'Confirm delete task?');
$l_edit = Yii::t('app', 'Edit');
$l_info_complete = Yii::t('app', 'You can edit task to continue!');
$l_info_run = Yii::t('app', 'Set pause task, <br/>before edit or delete task!');
?>

<div class="dushboard-task-list center-block">

    <? if (!empty($pages)): ?>
        <? include('taskListBtn.php') ?>
    <? endif; ?>

    <? if (!count($data)): ?>
        <p class="bg-primary"><?= Yii::t('app', 'Task list empty') ?></p>
    <? else: ?>
        <br/>
        <? $txtBlock = \Yii::t('app', 'Done');?>
        <div class="table-responsive">
            <table id="task-table" class="table table-hover table-striped" width="100%">
                <tbody>
                <? foreach ($data as $i): ?>

                    <? $social_data = $i->getPreviewData(); ?>
                    <tr id="tr<?= $social_data->id ?>" class="social_<?=$i->social?> type_<?=$i->type?> <?=$statusColor[$i->status]?>">
                        <td class="img">
                            <a href="<?= $i->social_link ?>" target="_blank" class="preview">
                                <img src="<?= $social_data->img ?>" alt="<?= $social_data->title ?>">
                            </a>

                        </td>
                        <td class="name">
                            <div><b><?= Yii::t('app', $i->getNameType()) ?></b></div>
                            <div><?= $social_data->title ?></div>
                            <div class="hinfo"><i><?= $i->comment ?></i></div>
                            <div class="hinfo"><i><?= Yii::$app->formatter->asDatetime($i->time_cr) ?></i></div>
                            <? if ($i->time_up): ?>
                                <div class="hinfo"><i><?= Yii::$app->formatter->asDatetime($i->time_up) ?></i></div>
                            <? endif; ?>
                        </td>
                        <td class="status">
                            <? if ($i->likes): ?>
                            <div class="progress ">
                                <? $psnt = (int)(100 * ($i->likes_spend / ($i->likes_spend + $i->likes))); ?>
                                <div class="progress-bar" role="progressbar" aria-valuenow="<?= $psnt ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $psnt ?>%"><?= $psnt ?>
                                    %
                                </div>
                            </div>
                            <? endif; ?>
                            <div class="hinfo"><?= $l_done ?> <?= ($i->likes_spend / $i->action_cost) ?> <?= $l_from  ?> <?= ( ($i->likes_spend + $i->likes) / $i->action_cost) ?></div>
                            <div class="hinfo"><?= $l_amount ?> <b><?= ($i->likes_spend + $i->likes) ?></b> <?= $l_likes ?> ; <?= $l_costForAction ?> - <b><?= $i->action_cost ?></b></div>
                        </td>
                        <td class="text-right">
                            <div>
                            <? if ($i->isBlocked()): ?>
                                <?= Yii::t('ban', $i->reason) ?>
                                <a class="btn btn-danger btn-icon <?= ($i->isRun() ? 'disabled' : '') ?>" href="<?= Url::to(['dashboard/task-delete', 'id' => $i->id]) ?>" title="<?= $l_delete ?>" data-confirm="<?= $l_confirm_delete ?>"><i class="fa fa-times"></i></a>
                            <? else: ?>

                                <? if($i->isPause()):?>
                                    <a class="btn btn-success btn-icon" href="<?= Url::to(['dashboard/task-reset', 'id' => $i->id]) ?>" title="<?= $l_complete ?>"><i class="fa fa-times"></i></a>
                                <? endif; ?>

                                <? $to = $i->getTimeOut(); ?>
                                <? $canPlay = ( ($i->isPause() || $i->isRun()) && !$to); ?>
                                <? if(0):?><a class="btn btn-primary btn-icon " href="##" title="<?= $l_stats ?>"><i class="fa fa-bar-chart"></i></a><? endif; ?>
                                <a class="btn btn-success btn-icon <?= ($canPlay ? '' : 'disabled') ?>" href="<?= Url::to(['dashboard/task-change-status', 'id' => $i->id]) ?>" title="<?= $l_pause ?>" data-countdown="<?= $to ?>"><i class="fa fa-<?= ($i->isRun() ? 'pause' : 'play') ?>"></i></a>
                                <a class="btn btn-primary btn-icon <?= (( ($i->isPause() || $i->isComplete()) && !$to) ? '' : 'disabled') ?>" href="<?= Url::to(['dashboard/task-edit', 'id' => $i->id]) ?>" title="<?= $l_edit ?>" data-countdown="<?= $to ?>"><i class="fa fa-pencil"></i></a>
                            <? endif; ?>

                                <a class="btn btn-info btn-icon" href="<?= Url::to(['dashboard/task-info', 'id' => $i->id]) ?>" title="<?= $l_info ?>"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div class="hinfo">
                                <? if($i->isRun()): ?>
                                    <?=$l_info_run?>
                                <? endif; ?>
                                <? if($i->isComplete()): ?>
                                    <?=$l_info_complete?>
                                <? endif; ?>
                            </div>
                        </td>
                    </tr>
                <? endforeach; ?>
                </tbody>
            </table>
        </div>
    <? endif; ?>

    <? if (!empty($pages) && count($data)>5): ?>
        <? include('taskListBtn.php') ?>
    <? endif; ?>

    <?php
    if (!empty($pages)) {
        echo LinkPager::widget([
            'pagination' => $pages,
            'registerLinkTags' => true,
            'maxButtonCount' => 6
        ]);
    }
    ?>
</div>
<script>
    onLoadArr.push(function () {

        // TASK LIST
        /**
         * Загрузка гифки при наведении
         */
        // $('a.preview').hover(
        //     hoverLoadGifOn,
        //     hoverLoadGifOff
        // );

        /**
         * Обртный отсчет
         */
        $('a[data-countdown]').each(eachCountDown);

    });
</script>