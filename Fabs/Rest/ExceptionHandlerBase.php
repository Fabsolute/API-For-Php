<?php

namespace Fabs\Rest;

use Fabs\Rest\Exceptions\Exception;

abstract class ExceptionHandlerBase extends Injectable
{
    /**
     * @param Exception $exception
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function handle($exception);
}
