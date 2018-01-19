<?php

namespace Test\App\IRobot\API;

use Fabs\Rest\APIBase;
use Test\App\IRobot\Middleware\LoggerMiddleware;

class ClientAPI extends APIBase
{
    public function initialize()
    {
        $this->registerAction('POST', '/ruh/{cus}/{yuh}', 'oha')
            ->addMiddleware(LoggerMiddleware::class,'action');
    }

    public function oha($cus, $yuh)
    {
        return 'wtf - ' . $cus . ' - ' . $yuh;
    }
}
