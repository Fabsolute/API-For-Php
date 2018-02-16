<?php

namespace Fabs\Rest\DI;

use Fabs\DI\Container;
use Fabs\Rest\Http\Request;
use Fabs\Rest\Http\Response;
use Fabs\Rest\Router;

class FactoryDefault extends Container
{
    public function __construct()
    {
        $this->setShared('router', Router::class);
        $this->setShared('request', function () {
            return Request::createFromGlobals();
        });
        $this->setShared('response', Response::class);
    }
}
