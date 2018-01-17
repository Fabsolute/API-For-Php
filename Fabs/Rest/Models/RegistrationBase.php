<?php


namespace Fabs\Rest\Models;


abstract class RegistrationBase
{
    public $instance = null;

    /** @var string[] */
    private $middleware_list = [];

    /**
     * @param string $middleware
     * @return static
     */
    public function addMiddleware($middleware)
    {
        $this->middleware_list[] = $middleware;
        return $this;
    }

    /**
     * @return string[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMiddlewareList()
    {
        return $this->middleware_list;
    }
}
