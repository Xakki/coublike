<?php
/**
 * Created by PhpStorm.
 * see http://coub.com/dev/docs/Coub+API/Data+stuctures
 * User: xakki
 * Date: 15.11.15
 */

namespace app\modules\Couber\models;

class CoubBigJson extends CoubSmallJson
{

    /**
     * the type of the coub's visibility; accepts one of the following values
     * public — the coub is visible for everyone;
     * friends — the coub is visible only for its creator's friends;
     * unlisted — the coub is accessible only via direct link;
     * private — the coub is visible only for its creator.
     * @var string
     */
    public $visibility_type;

    /**
     * the identifier of the channel that the coub belongs to;
     * @var int
     */
    public $channel_id;

    /**
     *  the time when the coub was created (UNIX-time)
     * @var int
     */
    public $created_at;

    /**
     * the time of the last coub's update (UNIX-time)
     * @var int
     */
    public $updated_at;

    /**
     * whether the coub's uploading process is completed
     * @var boolean
     */
    public $is_done;

    /**
     * the duration of the coub video in seconds
     * @var float
     */
    public $duration;

    /**
     * the number of times the coub was viewed
     * @var int
     */
    public $views_count;

    /**
     * whether the coub was chosen as the coub of the day;
     * @var boolean
     */
    public $cotd;

    /**
     * the date when the coub was chosen as the coub of the day (UNIX-time)
     * @var
     */
    public $cotd_at;

    /**
     * whether the coub was recoubed by the current channel;
     * @var boolean
     */
    public $recoub;

    /**
     *  whether the coub was liked by the the current channel;
     * @var boolean
     */
    public $like;

    /**
     *  the number of recoubs of the coub;
     * @var int
     */
    public $recoubs_count;

    /**
     * the number of likes the coub has received;
     * @var int
     */
    public $likes_count;

    /**
     *  the identifier of the original coub which the current coub is recoubed from
     * @var int
     */
    public $recoub_to;

    /**
     * whether the coub has any abuses
     * @var boolean
     */
    public $flag;

    /**
     * if the coub has the audio track from its source video;
     * @var boolean
     */
    public $original_sound;

    /**
     * whether the coub has an audio track;
     * @var boolean
     */
    public $has_sound;

    /**
     * the JSON object that describes versions of coub's video formats; it contains several nested JSON objects:
     * @var VideoFilesJson
     */
    public $file_versions;


    /**
     * the JSON object that describes versions of coub's audio formats;
     * @var
     */
    public $audio_versions;

    /**
     * the JSON object that describes versions of the audio track that relates to the flv-version of the coub video;
     * @var
     */
    public $flv_audio_versions;

    /**
     * the JSON object that describes available thumbnail images of the video;
     * @var ThumbnailImagesJson
     */
    public $image_versions;

    /**
     * the JSON object that describes the first frame of the coub;
     * @var ThumbnailImagesJson
     */
    public $first_frame_versions;

    /**
     * the JSON objects describes exact resolutions for every version of the coub video:
     * @var
     */
    public $dimensions;

    /**
     * whether the coub video has age restrictions set by its creator;
     * @var boolean
     */
    public $age_restricted;

    /**
     * whether the coub video has age restrictions set by the Coub administration;
     * @var boolean
     */
    public $age_restricted_by_admin;

    /**
     * whether the coub video is allowed to be used in other coubs;
     * @var boolean
     */
    public $allow_reuse;

    /**
     * whether the coub video is banned by the Coub administration;
     * @var boolean
     */
    public $banned;

    /**
     * the JSON objects that describes the external source of the video (in case if it is uploaded from a user computer, this field is set to false).
     * @var
     */
    public $external_download;

    /**
     *  the channel small JSON that describes the channel the video is uploaded to;
     * @var ChannelSmallJson
     */
    public $channel;

    /**
     * the percent of the completion of the video upload;
     * @var int
     */
    public $percent_done;

    /**
     *  the array of JSON objects that stores the data of tags added to the coub:
     * @var
     */
    public $tags;

    /**
     * the id of the uploaded video file's source;
     * @var int
     */
    public $raw_video_id;

    /**
     * the JSON object that stores the data relates to the sources of the coub's video and audio:
     * @var
     */
    public $media_blocks;

    /**
     *  the JSON object that stores the data relates to the source of the coub's video.
     * @var
     */
    public $external_video;

    /**
     * the URL to the source video thumbnail;
     * @var string
     */
    public $raw_video_thumbnail_url;

    /**
     * the title of the source video.
     * @var string
     */
    public $raw_video_title;



}