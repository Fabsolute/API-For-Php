<?php


namespace Fabs\Rest\ExceptionHandlers;


use Fabs\Rest\ExceptionHandlerBase;
use Fabs\Rest\Exceptions\StatusCodeException\InternalServerErrorException;

class ExceptionHandler extends ExceptionHandlerBase
{
    /**
     * @param \Exception $exception
     * @throws InternalServerErrorException
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function handle($exception)
    {
        throw new InternalServerErrorException($exception->getMessage());
    }
}