<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 10.11.15
 * Time: 1:24
 */

namespace app\modules\Couber\components;

use linslin\yii2\curl;

class Coub
{
    const API_BASE_URL = 'https://coub.com';

    const API_URL_GET = '/api/v2/coubs/:id';
    const API_URL_EDIT = '/api/v2/coubs/:id'; // POST
    const API_URL_DELETE = '/api/v2/coubs/:id';

    // http://coub.com/dev/docs/Coub+API/Likes
    const API_URL_LIKE = '/api/v2/likes'; // POST
    const API_URL_UNLIKE = '/api/v2/likes'; // DELETE

    const API_URL_FOLLOW = '/api/v2/follows'; // POST
    const API_URL_UNFOLLOW = '/api/v2/follows'; // DELETE

    const API_URL_CHANNEL = '/api/v2/channels/:id'; // GET
    /**
     * see https://github.com/linslin/yii2-curl
     * @var \linslin\yii2\curl\Curl
     */
    private static $curl = null;

    public static function initCurl() {
        if(!self::$curl) {
            self::$curl = new curl\Curl();
        }
    }


    /**
     * @param $id string
     * @return \app\modules\Couber\models\CoubBigJson
     */
    public static function getCoub($id) {
        self::initCurl();
        $url = str_replace(':id', $id, self::API_URL_GET);
        $result = self::$curl->get(self::API_BASE_URL.$url);
        return json_decode($result);
    }

    /**
     * @param $id string
     * @return \app\modules\Couber\models\CoubBigJson
     */
    public static function getChannel($id) {
        self::initCurl();
        $url = str_replace(':id', $id, self::API_URL_CHANNEL);
        $result = self::$curl->get(self::API_BASE_URL.$url);
        return json_decode($result);
    }

    /**
     * @param $id string
     * @return \app\modules\Couber\models\CoubBigJson
     */
    public static function getUserMe($id) {
        self::initCurl();
        $url = str_replace(':id', $id, self::API_URL_GET);
        $result = self::$curl->get(self::API_BASE_URL.$url);
        return json_decode($result);
    }

    /**
     * @param $id string
     * @return \app\modules\Couber\models\CoubBigJson
     */
    public static function doLike($id) {
        self::initCurl();
        $url = str_replace(':id', $id, self::API_URL_GET);
        $result = self::$curl->get(self::API_BASE_URL.$url);
        return json_decode($result);
    }
}