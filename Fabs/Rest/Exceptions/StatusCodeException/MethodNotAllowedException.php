<?php

namespace Fabs\Rest\Exceptions\StatusCodeException;

use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class MethodNotAllowedException extends StatusCodeException
{
    /**
     * MethodNotAllowedException constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(405, HttpStatusCodes::METHOD_NOT_ALLOWED, $error_details);
    }
}
