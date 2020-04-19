<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var onLoadArr = [];
    </script>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<?= (method_exists(Yii::$app->controller, 'renderFlushMessage') ? Yii::$app->controller->renderFlushMessage() : '') ?>

<? include __DIR__ . '/../widget/bar.php'; ?>

<p class="button-offcanvas visible-xs">
    <button type="button" class="btn" aria-label="Menu" id="leftMenu" title="Menu">
        <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
    </button>
</p>

<div class="container-fluid">
    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-xs-6 col-sm-3 col-md-2 sidebar sidebar-offcanvas">
            <div class="user-info">
                <img src="<?= Yii::$app->user->identity->getAvatar() ?>" alt="<?= Yii::$app->user->identity->getChannelTitle() ?>">
                <div class="user-info-name"><a href="<?= Yii::$app->user->identity->getChannelLink() ?>" target="_blank"><?= Yii::$app->user->identity->getChannelTitle() ?></a></div>
                <div class="user-info-likes">
                    <span class="userLikes">
                        <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                        <?= Yii::$app->user->identity->likes ?>
                    </span>
                    <? /*<a href="/dashboard/buy" class="btn btn-primary user-info-btn"><?=Yii::t('app','Buy')?></a>*/ ?>
                </div>
            </div>
            <? include __DIR__ . '/../widget/menu.php'; ?>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-10 main">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <div class="main-content">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<? include __DIR__ . '/../widget/foot.php'; ?>

<?php $this->endBody() ?>
<script>
    jQuery(document).ready(function () {
        jQuery('#leftMenu').click(function () {
            jQuery('.row-offcanvas').toggleClass('active')
        });
        if (onLoadArr.length) {
            for (var i in onLoadArr) {
                var src = onLoadArr[i];
                if (typeof src === 'function') {
                    src.call();
                }
                else if (window.execScript) {
                    window.execScript(src);
                } else {
                    console.error('Wrong onLoadArr item', src);
                }
            }
        }
    });
</script>
</body>
</html>
<?php $this->endPage() ?>
