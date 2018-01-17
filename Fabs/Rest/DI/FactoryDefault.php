<?php

namespace Fabs\Rest\DI;

use Fabs\Rest\DI;
use Fabs\Rest\Request;
use Fabs\Rest\Router;

class FactoryDefault extends DI
{
    public function __construct()
    {
        $this->set('router', Router::class);
        $this->set('request', Request::class);
    }
}
