<?php

/* @var $this \yii\web\View */
/* @var $content string */
// /user/login
// /user/auth?authclient=coub

use yii\bootstrap\Nav,
    yii\bootstrap\NavBar,
    \yii\helpers\Url;

$flags = '<li class="flags">';
foreach (Yii::$app->params['lang'] as $k=>$r) {
    if (LOC_TAG == $k)
        $flags .= '<a class="active" style="display: inline-block;"><img src="'.$r['img'].'" title="'.$r['title'].'"></a>';
    else
        $flags .= '<a style="display: inline-block;opacity:.5;" href="'.$r['url'].'" hreflang="'.$k.'" rel="alternate"><img src="'.$r['img'].'" title="'.$r['title'].'"></a>
';
}
$flags .= '</li>';

?>

<? if (Yii::$app->user->isGuest):
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = array();
    $menuItems[] = ['label' => \Yii::t('app', 'Home'), 'url' => ['/#home']];
//    $menuItems[] = ['label' => \Yii::t('app', 'Function'), 'url' => ['/#func']];
//    $menuItems[] = ['label' => \Yii::t('app', 'FAQ'), 'url' => ['/#faq']];
    $menuItems[] = ['label' => \Yii::t('app', 'Best coubs'), 'url' => ['/site/best-coubs']];
    $menuItems[] = ['label' => \Yii::t('app', 'Sign in'), 'url' => ['/user/auth?authclient=coub']];
    $menuItems[] = $flags;

    echo Nav::widget([
        'encodeLabels' => false,
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();

else: ?>

<nav class="navbar-inverse navbar-fixed-top navbar">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="/"><?= Yii::$app->name ?></a>
        </div>
        <div>
            <ul class="navbar-nav navbar-right nav">
                <? if (Yii::$app->user->identity->getIsAdmin()): ?>
                    <li><a href="?showSmpLogs=/">Logs</a></li>
                <? endif; ?>
                <li><a href="<?=Url::toRoute('/dashboard/free-likes');?>"><?= Yii::$app->user->identity->getChannelTitle() ?></a></li>
                <li><a href="<?=Url::toRoute('/site/best-coubs');?>"><?= \Yii::t('app', 'Best coubs') ?></a></li>
                <? if (Yii::$app->user->identity->getIsAdmin()): ?>
                    <li><a href="<?=Url::toRoute('/'.ADMIN_URL);?>"><?= \Yii::t('app', 'Admin') ?></a></li>
                <? endif; ?>
                <?=$flags?>
            </ul>
        </div>
    </div>
</nav>
<style>
    .navbar-header {
        float: left;
    }
    .navbar-right {
        float: right !important;
        margin: 0;
        margin-right: -15px;
    }
    .navbar-nav > li {
        float: left;
    }
    .navbar-nav > li > a {
        padding-top: 15px;
        padding-bottom: 15px;
    }
    @media screen and (max-width: 510px) {
        .navbar-nav > li {
            padding: 0 0 0 10px;
        }
        .navbar-nav > li > a {
            padding: 13px 0 0 3px;
        }
    }
    @media screen and (max-width: 325px) {
        .navbar-nav > li:first-child {
            display: none;
        }
    }
</style>
<? endif; ?>
