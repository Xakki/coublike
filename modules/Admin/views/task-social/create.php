<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TaskSocial */

$this->title = Yii::t('admin', 'Create Task Social');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'Task Socials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-social-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
