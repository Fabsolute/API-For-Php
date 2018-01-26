<?php

namespace Fabs\Rest\Exceptions\StatusCodeException;

use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class UnsupportedMediaTypeException extends StatusCodeException
{
    /**
     * UnsupportedMediaTypeException constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(415, HttpStatusCodes::UNSUPPORTED_MEDIA_TYPE, $error_details);
    }
}
