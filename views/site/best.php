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

$this->registerJsFile('/js/dashboard.js');
$this->registerCssFile('/css/dashboard.css');

$this->title = Yii::t('app', 'Best coubs');
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

<div class="dashboard-free-likes best-likes view-<?=$viewType?>">
    <h1><?=$this->title?></h1>

    <? if (!count($data)): ?>
        <div class="alert alert-info" style="max-width:300px;margin: 20px auto;"><?= Yii::t('app', 'list empty') ?></div>
    <? else: ?>
        <? if(1): ?>
            <div id="autoPlayBtn" style="position: absolute;right: 130px;top: 8px;">
                <? if (!isset($_COOKIE['autoPlayCoub'])) $_COOKIE['autoPlayCoub'] = 1; ?>
                Auto play <div class="my-switch"><input type="checkbox" value="1" <?=($_COOKIE['autoPlayCoub'] ? 'checked="checked"' : '')?>/><span class="toogle"></span></div>
            </div>
            <div id="view-list" class="btn-group">
                <? foreach ($viewList as $k=>$r): ?>
                    <button class="btn btn-default<?=( $k==$viewType? 'active' : '')?>" aria-label="<?=$k?>" data-view="<?=$k?>"><span class="glyphicon glyphicon-<?= $r ?>" aria-hidden="true"></span></button>
                <? endforeach; ?>
            </div>
        <? endif; ?>
        <div class="row row-block">

            <? foreach ($data as $i): ?>
                <? $social_data = $i->getPreviewData('small'); ?>
                <div class="col-block social_<?=$i->social?> isNew" data-id="<?=$i->id?>" data-duration="<?=ceil($social_data->duration)?>">
                    <div class="frame_block" data-iframe="<?=$i->getFrameUrl()?>">
                        <img class="frame_block_preview" src="<?= $social_data->img ?>"/>
                        <i class="playBtnPreview"></i>
                    </div>
                </div>
            <? endforeach; ?>

            <?php if (!empty($pages)):
                echo LinkPager::widget([
                    'options' => ['class' => 'pagination', 'style'=>'padding-bottom: 100px;'],
                    'pagination' => $pages,
                    'registerLinkTags' => true,
                    'maxButtonCount' => 6
                ]);
            endif; ?>
        </div>
    <? endif; ?>

</div>
<script>
    onLoadArr.push(function () {
        autoPlayCoub = <?=$_COOKIE['autoPlayCoub'] ? 'true' : 'false'?>;
        // GET FREE LIKES
        frameEventListen();
        $('body').on('click', '.playBtnPreview', previewClick);
        $('body').on('click', '.showMorePages button', showMorePages);
        $('body').on('click', '#view-list2 button', changeViewlist);
        $('body').on('click', '#view-list button', changeViewlist);
        $('#autoPlayBtn input').on('change', function(){
            if (this.checked) autoPlayCoub = true;
            else autoPlayCoub = false;
        });
    });

</script>


