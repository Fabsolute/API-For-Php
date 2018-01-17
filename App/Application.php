<?php


namespace Test\App;


use Fabs\Rest\Constants\KernelTypes;

class Application extends \Fabs\Rest\Application
{
    public function initialize()
    {
        $this->registerKernel(KernelTypes::CLI, Kernel::class);
    }
}