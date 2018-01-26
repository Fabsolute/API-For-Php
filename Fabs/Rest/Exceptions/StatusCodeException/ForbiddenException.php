<?php


namespace Fabs\Rest\Exceptions\StatusCodeException;


use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class ForbiddenException extends StatusCodeException
{
    /**
     * ForbiddenException constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(403, HttpStatusCodes::FORBIDDEN, $error_details);
    }
}
