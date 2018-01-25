<?php


namespace Test\App;

use Fabs\Rest\Kernel\RestKernel;
use Test\App\IRobot\IRobotModule;

class Kernel extends RestKernel
{
    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function initializeREST()
    {
        $this->defineModule('/irobot', IRobotModule::class);
    }
}

// todo middleware and parameters (handle, finish) can set (only, except)
// todo exception handler for custom exceptions
// todo service provider
// todo event listener (hmm)
// todo task
// todo jobs with task
// todo test for json api (acting session)
