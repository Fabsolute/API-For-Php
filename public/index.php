<?php

use Fabs\Rest\Application;

include '../vendor/autoload.php';

//$application = new \Test\App\Kernel();
$application = new Application(\Test\App\Kernel::class, \Fabs\Rest\Constants\KernelTypes::CLI);
$application->run();
