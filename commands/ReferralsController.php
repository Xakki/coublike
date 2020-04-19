<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User;

/**
 * ./yii referrals
 */
class ReferralsController extends Controller
{

    public function actionIndex()
    {
        // раз в сутки
        $query = User::find();
        $query->where('referral_id IS NOT NULL and updated_at > :updated_at ', [':updated_at' => (time() - 24 * 3600) ]);
        foreach ($query->all() as $row) {
            /** @var User $row */
            echo ' Update '.$row->referralTaxCommit().' referrals by user '.$row->id.PHP_EOL;
        }
        echo PHP_EOL;
    }


}
