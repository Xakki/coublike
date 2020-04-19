<?php
use dektrium\user\widgets\Connect;
/* @var $this yii\web\View */

$this->title = Yii::t('index', 'COUBLIKE - will help to promote the COUB');
$this->registerMetaTag([
    'name' => 'description',
    'content' => Yii::t('index','Promotion and up the video and channels at coub.com')], 'description'
);

$renderAbout = function($ru, $en) {
    if (!LOC_IS_RU) $ru = $en;
    ?>
    <div class="col-md-4 col-sm-6">
        <div class="about">
            <h3><?=$ru[0]?></h3>
            <ul>
                <li><?=implode('</li><li>', (array)$ru[1])?></li>
            </ul>
        </div>
    </div>
    <?
}
?>

<div class="container-fluid">
    <div class="first-page wpage row bg-silver-lighter" id="home">
        <div class="first-page-in">
            <?= Connect::widget([
                'baseAuthUrl' => ['/user/auth'],
            ]) ?>
            <a href="/user/auth?authclient=coub" class="first-page-l"><?=Yii::t('index', 'Likes')?> &middot; <?=Yii::t('index', 'Recoubs')?> &middot; <?=Yii::t('index', 'Followings')?> &middot; <?=Yii::t('index', 'Views')?></a>
            <div style="text-align: center;"><?=Yii::t('index', 'Best service for promotion and up video from COUB')?></div>
        </div>
    </div>

    <!-- -------------------------------  -->
    <div id="func"></div>
    <div class="wpage row" data-scrollview="true">
        <div class="container">
            <h2 class="content-title"><?=Yii::t('index', 'Basic function')?></h2>

            <div class="text-left">
            <? if(LOC_IS_RU):?>
                Авторизовавшись через coub.com вы сможете просматривать задания других пользователей и создавать свои задания.
                За каждое выполненное задание Вы получаете лайки на свой баланс.
                Заработанные таким образом лайки, Вы можете потратить на задания для других пользователей.
            <? else:?>
                Sharing between users <b>COUBLIKE</b>.
                For each completed task You receive likes on your balance.
                Earned likes You can spend on assignments for other users.
            <? endif;?>
            </div>
            <br>

            <?=$renderAbout(
                    ['Виды заданий', ['Лайки', 'Репосты', 'Подписки', 'Просмотры']],
                    ['Task types', ['Likes', 'Reposts', 'Subscribes', 'Views']])?>

            <?=$renderAbout(
                    ['Безопасность', ['Сервис не требует пароли', 'Никаких ботов']],
                    ['Safe', ['The service doesn\'t require passwords', 'No bots']])?>

            <?=$renderAbout(
                    ['Надежность', ['Гарантия просмотра', 'Проверка выполненных заданий']],
                    ['Reliability', ['Viewing guarantee', 'Check completed jobs']])?>

            <div id="faq"></div>
             <!-- begin container -->
            <div style="clear:both;padding-top: 30px;"></div>

            <h2 class="content-title">FAQ</h2>

            <p class="content-desc"></p>

            <p>
            <? if(LOC_IS_RU):?>
                Потребовалось продвижение в социальных сетях, но у вас возникло очень много вопросов? Сервис
                COUBLIKE предлагает вашему вниманию раздел FAQ, в котором вы найдёте все ответы, а также
                дополнительную информацию.
            <? else:?>
                It took the promotion in social networks, but you are having so many issues? Service
                COUBLIKE offers you FAQ where you will find all the answers, but also
                additional information.
            <? endif;?>
            </p>

            <p></p><br>
            <!-- begin row -->
            <div class="row">
                <!-- begin col-4 -->
                <div class="col-md-4 col-sm-4">
                    <div class="service">
                        <div class="icon bg-theme bounceIn contentAnimated">
                            <i class="fa fa-heart"></i></div>
                        <div class="info">
                            <h4 class="title">
                            <? if(LOC_IS_RU):?>
                                Что такое лайки и как получить лайки?
                            <? else:?>
                                What is likes and how to get likes?
                            <? endif;?>
                            </h4>

                            <p class="desc">
                            <? if(LOC_IS_RU):?>
                                Лайки - это внутренняя валюта их можно получить выполняя задания других пользователей.
                                Или купить в разделе купить лайки. Их можно потратить на задания.
                            <? else:?>
                                Likes - this is the currency you can obtain them from completing tasks of other users.
                                Or buy on page buy likes. They can spend on the job.
                            <? endif;?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end col-4 -->

                <!-- begin col-4 -->
                <div class="col-md-4 col-sm-4">
                    <div class="service">
                        <div class="icon bg-theme bounceIn contentAnimated">
                            <i class="fa fa-line-chart"></i></div>
                        <div class="info">
                            <h4 class="title">
                            <? if(LOC_IS_RU):?>
                                Какую цену ставить?
                            <? else:?>
                                What price to put?
                            <? endif;?>
                            </h4>

                            <p class="desc">
                            <? if(LOC_IS_RU):?>
                                На странице добавления задания есть рекомендованная цена. Вы можете поставить любую, но помните, чем выше цена тем выше скорость выполнения.
                            <? else:?>
                                On the page add a task there is a recommended price. You can supply any of them, but remember, the higher the price the higher the execution speed.
                            <? endif;?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end col-4 -->
                <!-- begin col-4 -->
                <div class="col-md-4 col-sm-4">
                    <div class="service">
                        <div class="icon bg-theme bounceIn contentAnimated">
                            <i class="fa fa-check-square"></i></div>
                        <div class="info">
                            <h4 class="title">
                            <? if(LOC_IS_RU):?>
                                Могу ли я удалять лайки, рекоубы и отписываться  после получения оплаты?
                            <? else:?>
                                Can I delete likes, and Recobe to unsubscribe after receiving the payment?
                            <? endif;?>
                            </h4>

                            <p class="desc">
                            <? if(LOC_IS_RU):?>
                                Нет, удалять лайки, рекоубы и отписываться запрещено.
                                За это вы получите штраф и не сможете добавлять задания пока не оплатите его.
                            <? else:?>
                                No, remove the "likes", and Recobe to unsubscribe is prohibited.
                                For this you will receive a penalty and will not be able to add tasks until you pay it.
                            <? endif;?>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- end col-4 -->

            </div>

            <div class="row">
                <div class="col-md-3 col-md-offset-4">
                    <a href="/user/auth?authclient=coub" class="btn btn-primary"><?=Yii::t('index', 'Start promotion you COUB')?></a>
                </div>
            </div>
            <!-- end row -->
            <!-- end container -->
            <br/>
            <p>
            <? if(LOC_IS_RU):?>
                По всем вопросам обращайтесь на почту <i>support@<?=HOST?></i>
            <? else:?>
                For all questions please e-mail <i>support@<?=HOST?></i>
            <? endif;?>
            </p>
            <br/>
        </div>
    </div>


</div>

<script>

    onLoadArr.push(function() {

        // $( window ).resize(function() {
        //     var docH = $(document).height() - 51;
        //     console.log(' docH = ', docH);
        //     $('.wpage').each(function() {
        //         if ($(this).height() < docH) {
        //             $(this).height(docH + 'px');
        //         }
        //         else {
        //             $(this).height('auto');
        //         }
        //     });
        // });
        // $( window ).resize();
    });
</script>

<style>

.coub.auth-link {
    text-decoration: none;
}
.coub.auth-link:after {
    display: block;
    content: "<?=Yii::t('app', 'OAuth авторизация через coub.com');?>";
    visibility: hidden;

    color: gray;
}
.coub.auth-link:hover:after {
    visibility: visible;
}
</style>