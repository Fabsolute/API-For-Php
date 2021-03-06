<?php

namespace Fabs\Rest\Exceptions\StatusCodeException;

use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class UnprocessableEntityException extends StatusCodeException
{
    /**
     * UnprocessableEntity constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(422, HttpStatusCodes::UNPROCESSABLE_ENTITY, $error_details);
    }
}
