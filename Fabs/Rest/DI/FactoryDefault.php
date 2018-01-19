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
        $this->set('router', Router::class,true);
        $this->set('request', Request::class,true);
        $this->set('response', Response::class,true);
    }
}
