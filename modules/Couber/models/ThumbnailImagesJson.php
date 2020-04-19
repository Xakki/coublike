<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class ThumbnailImagesJson
{
    /**
     * the template of the URLs to the files specified in this JSON;
     * @var string
     */
    public $template;

    /**
     * the array of strings that refer to available image versions:
     * @var array
     */
    public $versions;

    private $enum_versions_prof = [
        "medium",
        "medium_2x",
        "profile_pic",
        "profile_pic_2x",
        "profile_pic_new",
        "profile_pic_new_2x",
        "micro",
        "micro_2x",
        "tiny",
        "tiny_2x",
        "small",
        "small_2x",
        "ios_large",
        "ios_small"
    ];

    private $enum_versions_coub = [
        'micro',
        'tiny',
        'age_restricted',
        'ios_large',
        'ios_mosaic',
        'big',
        'med',
        'small',
        'pinterest'
    ];
}