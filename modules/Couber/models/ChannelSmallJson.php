<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class ChannelSmallJson
{
    /**
     * the identifier of the channel;
     * @var int
     */
    public $id;

    /**
     * the permalink of the channel;
     * @var string
     */
    public $permalink;

    /**
     * the description of the channel;
     * @var string
     */
    public $description;

    /**
     * the title of the channel;
     * @var string
     */
    public $title;

    /**
     * if this channel is followed by you;
     * @var boolean
     */
    public $i_follow_him;

    /**
     * the number of channel's followers;
     * @var int
     */
    public $followers_count;

    /**
     * the number of channels that the channel follows;
     * @var int
     */
    public $following_count;

    /**
     * the JSON object that contains data about channel's thumbnail images:
     * @var ThumbnailImagesJson
     */
    public $avatar_versions;

}