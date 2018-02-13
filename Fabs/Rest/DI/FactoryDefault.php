<?php

namespace Fabs\Rest\DI;

use Fabs\Rest\DI;
use Fabs\Rest\Http\Request;
use Fabs\Rest\Http\Response;
use Fabs\Rest\Router;

class FactoryDefault extends DI
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
