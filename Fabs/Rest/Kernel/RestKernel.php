<?php


namespace Fabs\Rest\Kernel;


use Fabs\Rest\ExceptionHandlers\StatusCodeExceptionHandler;
use Fabs\Rest\Exceptions\StatusCodeException;
use Fabs\Rest\KernelBase;
use Fabs\Rest\Middlewares\RESTMiddleware;

abstract class RestKernel extends KernelBase
{
    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected abstract function initializeREST();

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public final function initialize()
    {
        $this->getDefinition()
            ->addMiddleware(RESTMiddleware::class)
            ->setExceptionHandler(StatusCodeException::class, StatusCodeExceptionHandler::class);

        $this->initializeREST();
    }
}
