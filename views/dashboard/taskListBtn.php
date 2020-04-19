<?php
use yii\helpers\Url;
?>
<div class="text-center">
    <a href="<?=Url::to(['dashboard/task-add'])?>" class="btn btn-white"><i class="fa fa-plus"></i> <?= Yii::t('app', 'Add') ?></a>
    <a href="<?=Url::to(['dashboard/index'])?>" class="btn btn-white"><i class="fa fa-refresh"></i> <?= Yii::t('app', 'Refresh') ?></a>
    <a href="<?=Url::to(['dashboard/task-del-completed'])?>" class="btn btn-white"><i class="fa fa-trash"></i> <?= Yii::t('app', 'Delete completed') ?></a>
</div>
<div id="view-types">
    <div class="btn-group" role="group">
        <? foreach ($modeList as $k => $r): ?>
            <a class="btn <?= ($mode == $k ? 'btn-primary' : 'btn-default') ?>" href="?mode=<?= $k ?>"><?= $r ?></a>
        <? endforeach; ?>
    </div>
</div>