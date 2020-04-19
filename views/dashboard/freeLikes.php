<?php
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $data \app\models\TaskSocial[] */
/* @var $totalCount int */
/* @var $pageSize int */
/* @var $type int */
/* @var $tabCount array */
/* @var $social_data \app\modules\Couber\models\CoubBigJson */

$this->title = Yii::t('app', 'Free likes');
$this->params['breadcrumbs'][] = $this->title;
$viewList = [
    'th' => 'th-large',
    'film' => 'glyphicon glyphicon-film',
];
$viewType = 'th';
if (!empty($_COOKIE['view']) && isset($viewList[$_COOKIE['view']])) {
    $viewType = $_COOKIE['view'];
}

?>
<script>
    var idList = [];
</script>
<div class="dashboard-free-likes center-block view-<?=$viewType?>">
    <? if(1): ?>
    <div id="view-list" class="btn-group">
        <? foreach ($viewList as $k=>$r): ?>
            <button class="btn btn-default<?=( $k==$viewType? 'active' : '')?>" aria-label="<?=$k?>" data-view="<?=$k?>"><span class="glyphicon glyphicon-<?= $r ?>" aria-hidden="true"></span></button>
        <? endforeach; ?>
    </div>
    <? endif; ?>

    <div id="view-types">
        <div class="btn-group" role="group">
            <? foreach (\app\models\TaskSocial::getEnumType() as $k => $r): ?>
                <a class="btn <?= ($type == $k ? 'btn-primary' : 'btn-default') ?>" href="/dashboard/free-likes?type=<?= $k ?>"><?= $r ?><sup><?=$tabCount[$k]?></sup></a>
            <? endforeach; ?>
        </div>
    </div>

    <? if (!count($data)): ?>
        <div class="alert alert-info" style="max-width:300px;margin: 20px auto;"><?= Yii::t('app', 'Task list empty') ?></div>
    <? else: ?>
        <div class="row row-block" data-cnt="<?= $totalCount ?>">
            <? include('freeLikesItems.php'); ?>
        </div>
    <? endif; ?>

</div>
<script>
    onLoadArr.push(function () {
        // GET FREE LIKES
        frameEventListen();
        $('body').on('click', '.playBtnPreview', previewClick);
        $('body').on('click', '.getFreeLike', getFreeLike);
        $('body').on('click', '.ignoreLike', ignoreLike);
        $('body').on('click', '.showMorePages button', showMorePages);
        $('body').on('click', '#view-list button', changeViewlist);

    });

</script>


