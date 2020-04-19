<?php
/**
 * @author Xakki
 */

namespace app\modules\Couber\authclients;

use \dektrium\user\clients\ClientInterface;
use yii\base\Exception;
use Yii;
use \app\models\User;


class CoubAuth extends CoubOAuth implements ClientInterface
{
    /** @inheritdoc */
    public function getEmail()
    {
        return 'user'.$this->getUserAttributes()['id'].'@coub.com';
    }

    /** @inheritdoc */
    public function getUsername()
    {
        $name = isset($this->getUserAttributes()['name'])
            ? $this->getUserAttributes()['name']
            : null;
//        $name = isset($this->getUserAttributes()['current_channel']['title'])
//            ? $this->getUserAttributes()['current_channel']['title']
//            : null;
        return User::getNewUsername($name);
    }

    /**
     * Возвращаем обекты а не массивы
     * @param $apiSubUrl
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return mixed
     * @throws Exception
     */
    public function api2($apiSubUrl, $method = 'GET', array $params = [], array $headers = []) {
        try {
            $res = $this->api($apiSubUrl, $method, $params, $headers);
        } catch (Exception $e) {
            $res = $e->response->getData();
            if (!$res) throw $e;
        }
        $res = json_decode(json_encode($res));
        return $res;
    }

//    public function api3($url, $method = 'GET', array $params = [], array $headers = []) {
//        $accessToken = $this->getAccessToken();
//        $coubResponse = $this->apiInternal($accessToken, $url, $method, $params, $headers);
//        return $coubResponse;
//    }

//    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
//    {
//        if ($this->_responseObject) {
//            return Json::decode($rawResponse, false);
//        }
//        else {
//            return parent::processResponse($rawResponse, $contentType);
//        }
//    }

    /**
     * @return \app\modules\Couber\models\User|\app\modules\Couber\models\CoubResponse
     * @throws Exception
     */
    public function apiUserMe() {
        return $this->api2('users/me');
    }

    /**
     * @param $coubId
     * @param $channel_id
     * @return \app\modules\Couber\models\CoubResponse
     * @throws Exception
     */
    public function apiSetLike($coubId, $channel_id) {
        return $this->api2('likes', 'POST', [ 'id' => $coubId, 'channel_id' => $channel_id ]);
    }


    /**
     * @param $coubId
     * @param $channel_id
     * @return \app\modules\Couber\models\CoubBigJson
     * @throws Exception
     */
    public function apiSetRecoub($coubId, $channel_id) {
        return $this->api2('recoubs', 'POST', ['recoub_to_id' => $coubId, 'channel_id' => $channel_id]);
    }

    /**
     * @param $follow_channel_id
     * @param $my_channel_id
     * @return \app\modules\Couber\models\CoubResponse
     * @throws Exception
     */
    public function apiSetFollow($follow_channel_id, $my_channel_id) {
        return $this->api2('follows', 'POST', ['id' => $follow_channel_id, 'channel_id' => $my_channel_id]);
    }

    /**
     * Increment view
     * @deprecated не работает
     * @param $coubCode
     * @return int
     * @throws Exception
     */
    public function apiSetView($coubCode) {
        return $this->api2('https://coub.com/coubs/'.$coubCode.'/increment_views', 'POST', ['from' => 'fp']);
    }
//    public function setAccessToken($token)
//    {
//
//        $p = parent::setAccessToken($token);
//        print_r('<pre>');
//        print_r($token);
//        var_dump($p);
//        print_r('</pre>');
//        exit();
//        return $p;
//    }
}

class CoubResponse
{
    public $status;
}