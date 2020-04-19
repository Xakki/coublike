<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('user', 'Networks');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="center-block-400">
    <?php $auth = Connect::begin([
        'baseAuthUrl' => ['/user/auth'],
        'accounts'    => $user->accounts,
        'autoRender'  => false,
        'popupMode'   => false,
    ]) ?>
    <table class="table">
        <?php foreach ($auth->getClients() as $client): ?>
            <tr>
                <td style="width: 32px; vertical-align: middle">
                    <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]) ?>
                </td>
                <td style="vertical-align: middle">
                    <strong><?= $client->getTitle() ?></strong>
                </td>
                <td style="width: 120px">
                    <?= Html::a(Yii::t('user', ($auth->isConnected($client) ? 'Reconnect': 'Connect')), $auth->createClientUrl($client, true), [
                            'class' => 'btn btn-success btn-block',
                        ])
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php Connect::end() ?>
</div>
