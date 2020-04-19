<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TaskSocial */

$this->title = Yii::t('admin', 'Update {modelClass}: ', [
    'modelClass' => 'Task Social',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Task Socials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('admin', 'Update');
?>
<div class="task-social-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
