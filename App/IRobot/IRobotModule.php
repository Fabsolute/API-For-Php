<?php


namespace Test\App\IRobot;

use Fabs\Rest\DI;
use Fabs\Rest\ModuleBase;
use Test\App\IRobot\API\ClientAPI;

class IRobotModule extends ModuleBase
{
    public function initialize()
    {
        $this->registerAPI('/yuh',ClientAPI::class);
    }

    /**
     * @param $dependency_injector DI
     */
    public function registerServices($dependency_injector)
    {

    }
}
