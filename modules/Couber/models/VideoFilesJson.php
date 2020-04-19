<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class VideoFilesJson
{
    /**
     *  the JSON object that contains the coub data specific to the version of the video that intented to be displayed on web sites:
     * @deprecated
     * @var
     */
    public $web;

    /**
     * the JSON object that contains the coub data, specific to the version of the video intented to be displayed on web sites
     * that divided into several parts — chunks:
     * @deprecated
     * @var
     */
    public $web_chunks;

    /**
     * the JSON object that contains a coub data specific to the version of the video intented to be displayed on iOS devices.
     * @deprecated
     * @var
     */
    public $iphone;

    /**
     * the JSON object that contains the coub data specific to the HTML5 version of the coub video:
     * @var
     */
    public $html5;


    /**
     * the JSON object that contains the coub data specific to the version of the video intented to be displayed on mobile devices:
     * @var
     */
    public $mobile;

}