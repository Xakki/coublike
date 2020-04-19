<?php

namespace app\models;

use dektrium\user\models\Account as BaseAccount;
use Yii;
use yii\authclient\ClientInterface as BaseClientInterface;
use dektrium\user\clients\ClientInterface;
use yii\helpers\Json;

/**
 * Class User
 * @package app\models

 */
class Account extends BaseAccount
{

    public static function fetchAccount(BaseClientInterface $client)
    {
        /** @var \app\modules\Couber\authclients\CoubAuth $client */
        $account = static::getFinder()->findAccount()->byClient($client)->one();
        $updateAcc = [
            'provider'   => $client->getId(),

            'username' => $client->getUsername(),
            'email'    => $client->getEmail(),
            'token'    => $client->getAccessToken()->getToken(),
        ];
        $clientAttr = $client->getUserAttributes();
        if ($clientAttr && count($clientAttr)) {
            $updateAcc['client_id'] = $clientAttr['id'];
            $updateAcc['data'] = Json::encode($clientAttr);
        }
        else {
            \Yii::warning('Empty client data');
        }
        if (null === $account) {
            $updateAcc['class'] = static::className();
//            $updateAcc['created_at'] = time();
            $account = \Yii::createObject($updateAcc);
            $account->save(false);
        } else {
            $account->updateAttributes($updateAcc);
        }

        return $account;
    }

    public static function create(BaseClientInterface $client)
    {

        /** @var Account $account */
        $account = \Yii::createObject([
            'class'      => static::className(),
            'provider'   => $client->getId(),
            'client_id'  => $client->getUserAttributes()['id'],
            'data'       => Json::encode($client->getUserAttributes()),
            'token'    => $client->getAccessToken()->getToken(),
        ]);

        if ($client instanceof ClientInterface) {
            $account->setAttributes([
                'username' => $client->getUsername(),
                'email'    => $client->getEmail(),
            ], false);
        }

        if (($user = static::fetchUser($account)) instanceof User) {
            $account->user_id = $user->id;
        }

        $account->save(false);

        return $account;
    }

    protected static function fetchUser(BaseAccount $account)
    {
        if (!\Yii::$app->user->isGuest) {
            return \Yii::$app->user;
        }
        return parent::fetchUser($account);
    }
}