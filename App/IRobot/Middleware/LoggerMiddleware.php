<?php


namespace Test\App\IRobot\Middleware;


use Fabs\Rest\MiddlewareBase;

class LoggerMiddleware extends MiddlewareBase
{
    private $logger_name = null;

    public function __construct($logger_name)
    {
        $this->logger_name = $logger_name;
    }

    public function initialize()
    {
        var_dump($this->logger_name . ' has initialized');
    }

    public function finalize()
    {
        var_dump($this->logger_name . ' has finalized');

    }
}