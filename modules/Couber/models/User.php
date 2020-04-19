<?php
/**
 * Created by PhpStorm.
 * User: xakki
 * Date: 15.11.15
 * Time: 15:53
 */

namespace app\modules\Couber\models;

class User
{
    /**
     * the identifier of the user;
     * @var int
     */
    public $id;

    /**
     * the permalink of the user;
     * @var string
     */
    public $permalink;

    /**
     * the name of the user;
     * @var string
     */
    public $name;

    /**
     * the gender of the user, can be set to one of the following values: male, female, unspecified;
     * @var string
     */
    public $sex;

    /**
     * the city that the user specified in the profile;
     * @var string
     */
    public $city;

    /**
     * the channel small JSON relates to the channel that currently used by the user;
     * @var ChannelSmallJson
     */
    public $current_channel;

    /**
     * the time when the user profile was created;
     * @var string
     */
    public $created_at;

    /**
     * the time of the user profile's last update;
     * @var string
     */
    public $updated_at;

    /**
     * the current access token.
     * @var string
     */
    public $api_token;

    /**
     * @var ChannelBigJson[]
     */
    public $channels;


}