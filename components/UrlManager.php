<?php
/**
 * User: xakki
 * Date: 10.11.15
 */

namespace app\components;

class UrlManager extends \yii\web\UrlManager
{
    public $urlRewrite = [];
    public function createUrl($params)
    {
        $res = parent::createUrl($params);
        if (count($this->urlRewrite)) {
            foreach ($this->urlRewrite as $k=>$r) {
                if (strpos($res, $k)!==false) {
                    $res = str_replace($k, $r, $res);
                    break;
                }
            }
        }
//        if (count($this->rules)) {
//            // todo
//        }
        return $res;
    }
}