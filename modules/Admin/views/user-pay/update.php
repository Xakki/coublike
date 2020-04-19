<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\UserPay\models\UserPay */

$this->title = Yii::t('admin', 'Update {modelClass}: ', [
    'modelClass' => 'User Pay',
]) . ' ' . $model->up_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'User Pays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->up_id, 'url' => ['view', 'id' => $model->up_id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Update');
?>
<div class="user-pay-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
