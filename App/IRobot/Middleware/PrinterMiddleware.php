<?php


namespace Test\App\IRobot\Middleware;


use Fabs\Rest\MiddlewareBase;

class PrinterMiddleware extends MiddlewareBase
{
    public function finalize()
    {
        var_dump($this->response->getReturnedValue());
        exit;
    }
}