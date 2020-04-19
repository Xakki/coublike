<?php

namespace app\modules\Couber\models;

class CoubResponse
{
    /**
     * status
     * @var string
     */
    public $status;

    /**
     * errors
     * @var string
     */
    public $errors;

    /**
     * validation_errors
     * @var CoubResponseErrorValidation
     */
    public $validation_errors;

}

class CoubResponseErrorValidation
{
    /**
     * channel_id
     * @var array
     */
    public $channel_id;
}
