<?php

namespace Test\App;

use Fabs\Rest\Constants\KernelTypes;
use Test\App\IRobot\Middleware\PrinterMiddleware;

class Application extends \Fabs\Rest\Application
{
    public function initialize()
    {
        $this->defineKernel(KernelTypes::WEB, Kernel::class);
    }
}