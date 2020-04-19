<?php
use dektrium\user\widgets\Connect;
/* @var $this yii\web\View */

?>

<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/auth'],
        ]) ?>
    </div>
</div>
