<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\UserPay\models\UserPay */

$this->title = Yii::t('admin', 'Create User Pay');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'User Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-pay-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
