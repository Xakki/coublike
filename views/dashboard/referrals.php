<?php
/* @var $this yii\web\View */
/** @var \app\models\User[] $referrals */
$this->title = Yii::t('app', 'Referrals');
$this->params['breadcrumbs'][] = $this->title;

?>

<br/>
<? if(LOC_IS_RU):?>
    <p>Рефералы - это пользователи, которые первый раз зашли на <i><?=HOST?></i> по вашей реферальной ссылке.  За переход Вы получаете
    <b>+<?=Yii::$app->params['earnLikeGift']?>%</b> от всех заработанных  вашими рефералами лайков, <b>+<?=Yii::$app->params['earnBuyGift']?>%</b> от купленных лайков. (Расчитываются раз в 24 часа). Внимание, пользователи, которые зашли
    на сайт с вашего компьютера или уже заходили на сайт раньше  не считаются уникальными и за них бонус не начисляется.
</p>
    <h5>Как набрать рефералов?</h5>
    <p>
    Чтобы стать вашим рефералом, пользователь должен перейти на <i><?=HOST?></i> по вашей реферальной ссылке:
</p>
<? else:?>
    <p>Referrals are users who first time come on <i><?=HOST?></i> on your referral link. For an upgrade You get <b>+<?=Yii::$app->params['earnLikeGift']?>%</b> from all your referrals earned likes, <b>+<?=Yii::$app->params['earnBuyGift']?>%</b> of purchased likes. (Calculated every 24 hours). Attention users who are logged into the website with your computer or have already visited the website previously, not considered unique and the bonus is not calculated.</p>
    <h5>How to get referrals?</h5>
    <p>To become your referral, the user must go to <i><?=HOST?></i> your referral link:
</p>



<? endif;?>

<div>
    <div>
        <h4 class="p-l-20 p-r-20"><input type="text" onclick="this.select();" class="form-control" value="https://<?=HOST?>/?r=<?=Yii::$app->user->id?>"></h4>
    </div>
</div>
<br/>

<h5><?=Yii::t('app', 'A list of your referrals')?>:</h5>

<table class="table table-striped" id="act-table">
    <thead>
    <tr>
        <th style="vertical-align: top;"><?=Yii::t('app', 'Registration date')?></th>
        <th><?=Yii::t('app', 'Referral')?></th>
        <th><?=Yii::t('app', 'Passed by reference')?></th>
        <th><?=Yii::t('app', '% of earnings')?></th>
        <th><?=Yii::t('app', '% of purchases')?></th>
    </tr>
    </thead>
    <tbody>
    <? if (count($referrals)): ?>
        <? foreach ($referrals as $item): ?>
            <tr>
                <td><?=date('Y-m-d H:i:s', $item->created_at)?></td>
                <td><?=$item->id?></td>
                <td><?=_e($item->referral_url)?></td>
                <td><?=$item->referral_earn?></td>
                <td><?=$item->referral_buy?></td>
            </tr>
        <? endforeach; ?>
    <? else: ?>
        <tr><td colspan="5" class="text-center"><?=Yii::t('app', 'No data')?></td></tr>
    <? endif; ?>
    </tbody>
</table>