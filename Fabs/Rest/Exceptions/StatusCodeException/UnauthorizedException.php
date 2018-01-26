<?php


namespace Fabs\Rest\Exceptions\StatusCodeException;


use Fabs\Rest\Constants\HttpStatusCodes;
use Fabs\Rest\Exceptions\StatusCodeException;

class UnauthorizedException extends StatusCodeException
{
    /**
     * UnauthorizedException constructor.
     * @param mixed $error_details
     */
    public function __construct($error_details = null)
    {
        parent::__construct(401, HttpStatusCodes::UNAUTHORIZED, $error_details);
    }
}
