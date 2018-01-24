<?php

namespace Test\App\IRobot\API;

use Fabs\Rest\APIBase;
use Fabs\Rest\Constants\HttpMethods;
use Test\App\IRobot\Middleware\LoggerMiddleware;

class ClientAPI extends APIBase
{
    public function initialize()
    {
        $this->defineAction(HttpMethods::GET, '/ruh/{cus}/{yuh}', 'oha');
    }

    public function oha($cus, $yuh)
    {
        return ['wtf' => true, 'cus' => $cus, 'yuh' => $yuh];
    }
}
