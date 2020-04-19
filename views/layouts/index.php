<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\IndexAsset;

IndexAsset::register($this);
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

<?include __DIR__.'/../widget/bar.php';?>

<?= $content ?>

<?include __DIR__.'/../widget/foot.php';?>

<?php $this->endBody() ?>
<script>
    jQuery(document).ready(function () {
        if (onLoadArr.length) {
            for (var i in onLoadArr) {
                var src = onLoadArr[i];
                if (typeof src == 'function') {
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
