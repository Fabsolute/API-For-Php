<?php


namespace Test\App\IRobot;

use Fabs\Rest\DI;
use Fabs\Rest\ModuleBase;
use Test\App\IRobot\API\ClientAPI;
use Test\App\IRobot\Middleware\LoggerMiddleware;

class IRobotModule extends ModuleBase
{
    public function initialize()
    {
        $this->defineAPI('/yuh', ClientAPI::class);
    }

    /**
     * @param $dependency_injector DI
     */
    public function defineServices($dependency_injector)
    {

    }
}
