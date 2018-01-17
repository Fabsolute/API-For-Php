<?php

namespace Test\App\IRobot\API;

use Fabs\Rest\APIBase;

class ClientAPI extends APIBase
{
    public function initialize()
    {
        $this->registerAction('GET', '/ruh/{cus}/{yuh}', 'oha');
    }

    public function oha($cus, $yuh)
    {
        return 'wtf - ' . $cus . ' - ' . $yuh ;
    }
}
