<?php
/**
 * User: xakki
 * Date: 10.11.15
 */

namespace app\components;

class GoogleApi
{

    const API_URL_TINY = 'https://www.googleapis.com/urlshortener/v1/url';

    /**
     * @see https://console.developers.google.com/home
     */
    public $apiKey;

    /**
     * @param $longUrl
     * @return GoogleResponseApiSetUrlTiny | GoogleResponseApiError
     */
    public function setTinyUrl($longUrl)
    {
        //Long to Short URL
        $postData = array('longUrl' => $longUrl);

        $info = self::httpsPost($postData, self::API_URL_TINY . '?key='.$this->apiKey);
        return $info;
    }

    /**
     * @param $shortUrl
     * @return GoogleResponseApiGetUrlInfo | GoogleResponseApiError
     */
    public function getTinyUrlInfo($shortUrl)
    {
        //Short URL Information
        $params = array('shortUrl' => $shortUrl, 'key' => $this->apiKey, 'projection' => "ANALYTICS_CLICKS");
        $info = self::httpGet($params, self::API_URL_TINY);
        return $info;
    }

    /**
     * @param $shortUrl
     * @return GoogleResponseApiGetUrlInfoFull | GoogleResponseApiError
     */
    public function getTinyUrlInfoFull($shortUrl)
    {
        //Get Full Details of the short URL
//        $shortUrl="http://goo.gl/eDcZI";
        $params = array('shortUrl' => $shortUrl, 'key' => $this->apiKey, 'projection' => "FULL");
        $info = self::httpGet($params, self::API_URL_TINY);
        return $info;
    }

    /**
     * @param $postData
     * @param $apiUrl
     * @return mixed
     */
    public static function httpsPost($postData, $apiUrl)
    {
        $curlObj = curl_init();

        $jsonData = json_encode($postData);

        curl_setopt($curlObj, CURLOPT_URL, $apiUrl);
        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
        curl_setopt($curlObj, CURLOPT_POST, 1);
        curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

        $response = curl_exec($curlObj);

        //change the response json string to object
        $json = json_decode($response);
        curl_close($curlObj);

        if (isset($json->error) && $json->error->code==403) {
            // TODO : send EMail for admin - about limit
        }

        return $json;
    }

    /**
     * @param $params
     * @param $apiUrl
     * @return mixed
     */
    public static function httpGet($params, $apiUrl)
    {
        $final_url = $apiUrl . '?' . http_build_query($params);

        $curlObj = curl_init($final_url);

        curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlObj, CURLOPT_HEADER, 0);
        curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));

        $response = curl_exec($curlObj);

//change the response json string to object
        $json = json_decode($response);
        curl_close($curlObj);

        return $json;
    }
}

/**
 * Class GoogleResponseApiSetUrlTiny
 * @package app\components
 * @see https://developers.google.com/url-shortener/v1/getting_started#errors
 */
class GoogleResponseApiSetUrlTiny
{
    /**
     * @var string
     */
    public $kind;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $longUrl;
}

class GoogleResponseApiGetUrlInfo extends GoogleResponseApiSetUrlTiny
{
    /**
     * OK or MALWARE
     * @var string
     */
    public $status;

}

class GoogleResponseApiGetUrlInfoFull extends GoogleResponseApiGetUrlInfo
{
    /**
     * like 2009-12-13T07:22:55.000+00:00
     * @var string
     */
    public $created;

    /**
     *
     * @var GoogleResponseApiAnalytics
     */
    public $analytics;


}

class GoogleResponseApiAnalytics
{

    /**
     *
     * @var GoogleResponseApiAnalyticsInfo
     */
    public $allTime;

    /**
     *
     * @var GoogleResponseApiAnalyticsInfo
     */
    public $month;

    /**
     *
     * @var GoogleResponseApiAnalyticsInfo
     */
    public $week;

    /**
     *
     * @var GoogleResponseApiAnalyticsInfo
     */
    public $day;

    /**
     *
     * @var GoogleResponseApiAnalyticsInfo
     */
    public $twoHours;

}

class GoogleResponseApiAnalyticsInfo
{
    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $shortUrlClicks;

    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $longUrlClicks;

    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $referrers;

    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $countries;

    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $browsers;

    /**
     * @var GoogleResponseApiAnalyticsInfoData
     */
    public $platforms;
}

class GoogleResponseApiAnalyticsInfoData
{
    /**
     * @var string
     */
    public $count;

    /**
     * @var string
     */
    public $id;
}

class GoogleResponseApiError
{
    /**
     * @var GoogleResponseApiErrorInfo
     */
    public $error;
}


class GoogleResponseApiErrorInfo
{

    /**
     * @var []
     */
    public $errors;

    /**
     * @var int
     */
    public $code;

    /**
     * @var string
     */
    public $message;
}