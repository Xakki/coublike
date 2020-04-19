<?php


/* @var $this \yii\web\View */
/* @var $content string */

use yii\widgets\Menu;
if (empty(Yii::$app->controller->isBackend)) :
    echo Menu::widget([
        'items' => [
            ['label' => '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> '.\Yii::t('app', 'My page'), 'url' => ['/dashboard/index']],
//            ['label' => '<span class="glyphicon glyphicon-usd" aria-hidden="true"></span> '.\Yii::t('app', 'Buy likes'), 'url' => ['/dashboard/buy']],
            ['label' => '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> '.\Yii::t('app', 'Task add'), 'url' => ['/dashboard/task-add']],
            ['label' => '<span class="glyphicon glyphicon-heart" aria-hidden="true"></span> '.\Yii::t('app', 'Get free likes'), 'url' => ['/dashboard/free-likes']],
            ['label' => '<span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> '.\Yii::t('app', 'Referrals'), 'url' => ['/dashboard/referrals']],
            ['label' => '<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> '.\Yii::t('app', 'Settings'), 'url' => ['/user/settings/networks']],
            ['label' => '<span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> '.\Yii::t('app', 'Support') . \app\modules\Support\controllers\UserController::getCountUnread(), 'url' => ['/dashboard/support']],
            ['label' => '<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> '.\Yii::t('app', 'Sign out'), 'url' => ['/site/logout']],
        ],
        'options' => array( 'class' => 'nav nav-sidebar' ),
        'encodeLabels' => false,
    ]);
else :
    echo Menu::widget([
        'items' => [
            ['label' => '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> '.\Yii::t('app', 'Admin'), 'url' => ['/'.ADMIN_URL.'/default/index']],
            ['label' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> '.\Yii::t('app', 'Users'), 'url' => ['/'.ADMIN_URL.'/user/index']],
            ['label' => '<span class="glyphicon glyphicon-usd" aria-hidden="true"></span> '.\Yii::t('app', 'Payment'), 'url' => ['/'.ADMIN_URL.'/user-pay/index']],
            ['label' => '<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span> '.\Yii::t('app', 'Task'), 'url' => ['/'.ADMIN_URL.'/task-social/index']],
            ['label' => '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> '.\Yii::t('app', 'Support'), 'url' => ['/'.ADMIN_URL.'/support/index']],
        ],
        'options' => array( 'class' => 'nav nav-sidebar' ),
        'encodeLabels' => false,
    ]);
endif;