<?php


namespace Fabs\Rest\Kernel;


use Fabs\Rest\ExceptionHandlers\ExceptionHandler;
use Fabs\Rest\ExceptionHandlers\StatusCodeExceptionHandler;
use Fabs\Rest\Exceptions\StatusCodeException;
use Fabs\Rest\KernelBase;
use Fabs\Rest\Middlewares\IncludeMiddleware;
use Fabs\Rest\Middlewares\JSONMiddleware;
use Fabs\Rest\Middlewares\QueryMiddleware;

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
            ->addMiddleware(JSONMiddleware::class)
            ->addMiddleware(QueryMiddleware::class)
            ->addMiddleware(IncludeMiddleware::class);

        $this->setExceptionHandler(StatusCodeException::class, StatusCodeExceptionHandler::class)
            ->setExceptionHandler(\Exception::class, ExceptionHandler::class);

        $this->initializeREST();
    }
}
