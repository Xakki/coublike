<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\Couber\authclients;

use yii\authclient\OAuth2;

/**
 * GoogleOAuth allows authentication via Google OAuth.
 *
 * In order to use Google OAuth you must create a project at <https://console.developers.google.com/project>
 * and setup its credentials at <https://console.developers.google.com/project/[yourProjectId]/apiui/credential>.
 * In order to enable using scopes for retrieving user attributes, you should also enable Google+ API at
 * <https://console.developers.google.com/project/[yourProjectId]/apiui/api/plus>
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'google' => [
 *                 'class' => 'yii\authclient\clients\CoubOAuth',
 *                 'clientId' => 'coub_client_id',
 *                 'clientSecret' => 'coub_client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see http://coub.com/dev/docs/Coub+API/Overview
 *
 * @author xakki
 * @since 1.0
 */
class CoubOAuth extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://coub.com/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://coub.com/oauth/token';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://coub.com/api/v2';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'logged_in',
//                'create',
                'recoub',
                'follow',
//                'channel_edit',
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('users/me', 'GET');
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'coub';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Coub';
    }

//    public function beforeApiRequestSend($event)
//    {
//        $event->request->setFormat(\yii\httpclient\Client::FORMAT_JSON);
//        parent::beforeApiRequestSend($event);
//    }
}
