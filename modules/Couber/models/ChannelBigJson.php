<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class ChannelBigJson extends ChannelSmallJson
{


    /**
     * the number of recoubs the channel have;
     * @var int
     */
    public $recoubs_count;

    /**
     * the time of the channel's last update (UNIX-time)
     * @var int
     */
    public $updated_at;

    /**
     * the time when the channel was created (UNIX-time)
     * @var int
     */
    public $created_at;


    /**
     * whether the channel follows you;
     * @var boolean
     */
    public $he_follows_me;

    /**
     * the number of original coubs (not recoubs) of the channel;
     * @var int
     */
    public $simple_coubs_count;

    /**
     * the number of likes the channel have received;
     * @var int
     */
    public $likes_count;


    /**
     * the JSON contains channel's authentication data:
     * id (integer) — the identifier of the user — the channel's owner;
     * channel_id (integer) — the identifier of the channel;
     * provider (string) — the authentication provider of the channel's owner account;
     * username_from_provider (string) — the username of the channel's owner in the authentication provider service.
     * @var array
     */
    public $authentications;

    /**
     * the JSON object contains string fields with user homepage URL and social networks profile names (if user have no profile the string will be empty):
     * @var array
     */
    public $contacts;

}