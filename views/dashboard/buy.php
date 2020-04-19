<?php
/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Buy likes');
$this->params['breadcrumbs'][] = $this->title;

/* @var $UserPayModule \app\modules\UserPay\Module */
$UserPayModule = \Yii::$app->getModule('userpay');

?>
<p>
    <a class="btn btn-sm btn-primary m-r-5" href="<?=\yii\helpers\Url::to('/userpay/default/history')?>"><?=\Yii::t('app', 'Payment history')?></a>
</p>
<div class="dushboard-addTask center-block-600">
     <p>
    <? if(LOC_IS_RU):?>
        Нажмите на кнопку <b>КУПИТЬ</b> напротив нужного количества лайков. Лайки будут переведены на ваш счет в
        ближайшие 5 минут после оплаты.
    <? else:?>
        Click on the button <b>BUY</b> next to the desired number of likes. Likes will be transferred to your account in
        the next 5 minutes after payment.
    <? endif;?>
    </p>

    <h4><?=\Yii::t('app', 'Ready offer')?>:</h4>

    <table id="buy_table" style="margin: auto;">
        <tbody>
        <? foreach ($UserPayModule->pack as $like => $bonus): ?>
            <tr>
                <td class="text-right"><?= $like ?> <?=\Yii::t('app', 'likes')?> </td>
                <td class="text-right">
                    <? if ($bonus):?>
                        <span class="badge badge-success badge-square f-s-10 m-r-5 m-l-5" title="<?=\Yii::t('app', 'Bonus')?>">+<?= ($like * $bonus / 100) ?><i class="fa fa-heart-o"></i></span>
                    <? endif; ?>
                </td>
                <td class="text-right">
                    за <?= $UserPayModule->getLikesCost($like) ?> <?= $UserPayModule->currency ?>
                </td>
                <td>
                    <button onclick="buyLikes(<?=$like?>)" class="btn btn-sm btn-primary m-l-10 m-4"><b><?=\Yii::t('app', 'Buy')?></b></button>
                </td>
            </tr>
            <tr></tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <h4><?=Yii::t('app', 'Or select the number of likes')?>:</h4>

    <form class="form-horizontal m-r-10" onsubmit="return false">

        <div class="form-group">
            <label class="col-md-4 control-label"><?=\Yii::t('app', 'Rubles')?>:</label>

            <div class="col-md-8">
                <input type="text" class="form-control" id="inpt-cost" placeholder="<?=\Yii::t('app', 'from {0} to {1}', [$UserPayModule->minCost, 10000])?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label"><?=\Yii::t('app', 'Likes')?>:</label>

            <div class="col-md-8">
                <input type="text" class="form-control" id="inpt-likes" placeholder="<?=\Yii::t('app', 'from {0} to {1}', [($UserPayModule->minCost * $UserPayModule->curs), 100000])?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label"><?=\Yii::t('app', 'Bonus %')?>:</label>

            <div class="col-md-8">
                <input disabled="disabled" type="text" class="form-control" id="inpt-disc" placeholder="%">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label"><?=\Yii::t('app', 'Bonus likes')?>:</label>

            <div class="col-md-8">
                <input disabled="disabled" type="text" class="form-control" id="inpt-bonus" placeholder="<?=\Yii::t('app', 'as a gift')?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label"><?=\Yii::t('app', 'Total, likes')?>:</label>

            <div class="col-md-8">
                <input disabled="disabled" type="text" class="form-control" id="inpt-total">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-8 col-md-offset-4" style="height: 40px;">
                <button class="btn btn-sm btn-primary m-r-5" id="btnBuy" onclick="buyLikes($('#inpt-likes').val())"><b><?=\Yii::t('app', 'Buy')?></b></button>
                <p class="bg-danger" id="ahtung" style="display: none; float: right; width: 80%;">Минимальный заказ должен быть на сумму не меньше  <?=$UserPayModule->minCost?> <?=$UserPayModule->currency?> или <?=($UserPayModule->minCost * $UserPayModule->curs)?> лайков</p>
            </div>
        </div>


    </form>
</div>

<script>
    var buyConfig = {
        minLikes : <?=($UserPayModule->minCost * $UserPayModule->curs)?>,
        minCost : <?=$UserPayModule->minCost?>,
        curs : <?=$UserPayModule->curs?>,
        pack: <?=json_encode($UserPayModule->pack)?>
    };
    onLoadArr.push(function() {
        $('#inpt-cost, #inpt-likes').on('keyup', buyFormRecount);
    });
</script>

<style>
    .badge.badge-square {
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0
    }

    .badge.badge-default, .label.label-default {
        background: #b6c2c9
    }

    .badge.badge-danger, .label.label-danger {
        background: #ff5b57
    }

    .badge.badge-warning, .label.label-warning {
        background: #f59c1a
    }

    .badge.badge-success, .label.label-success {
        background: #00acac
    }

    .badge.badge-info, .label.label-info {
        background: #49b6d6
    }

    .badge.badge-primary, .label.label-primary {
        background: #348fe2
    }

    .badge.badge-inverse, .label.label-inverse {
        background: #2d353c
    }

    .btn.btn-primary {
        color: #fff;
        background: #348fe2;
        border-color: #348fe2;
    }

    .m-l-10 {
        margin-left: 10px !important;
    }

    .m-4 {
        margin: 4px !important;
    }

    .btn {
        font-weight: 300;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
</style>