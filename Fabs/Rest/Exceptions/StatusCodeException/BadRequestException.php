<?php

namespace Fabs\Rest\Exceptions\StatusCodeException;

use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class BadRequestException extends StatusCodeException
{
    /**
     * BadRequestException constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(400, HttpStatusCodes::BAD_REQUEST, $error_details);
    }
}
