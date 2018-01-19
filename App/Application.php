<?php

namespace Test\App;

use Fabs\Rest\Constants\KernelTypes;
use Test\App\IRobot\Middleware\PrinterMiddleware;

class Application extends \Fabs\Rest\Application
{
    public function initialize()
    {
        $this->registerKernel(KernelTypes::WEB, Kernel::class)
            ->addMiddleware(PrinterMiddleware::class);
    }
}