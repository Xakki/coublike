<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class CoubSmallJson
{
    /**
     * the coub identifier
     * @var int
     */
    public $id;

    /**
     * Coub::Simple by default or Coub::Recoubif it is a recoub  or Coub::Temp
     * @var string
     */
    public $type;

    /**
     * the permalink of the coub
     * @see permalink of the coub
     * @var string
     */
    public $permalink;

    /**
     *  the title of the coub
     * @var string
     */
    public $title;

    /**
     * the JSON object that describes available thumbnail images of the video;
     * @var ThumbnailImagesJson
     */
    public $image_versions;

    /**
     *  the channel small JSON that describes the channel, the video is upload to:
     * @var ChannelSmallJson
     */
    public $channel;
}