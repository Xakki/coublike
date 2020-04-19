<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use yii\helpers\Json,
    \yii\httpclient\Response;


class JsonParser extends \yii\httpclient\JsonParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Response $response)
    {
        exit('+++----');
        return Json::decode($response->getContent(), false);
    }
}