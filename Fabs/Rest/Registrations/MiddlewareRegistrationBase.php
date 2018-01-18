<?php


namespace Fabs\Rest\Registrations;


use Fabs\Rest\MiddlewareBase;

abstract class MiddlewareRegistrationBase extends RegistrationBase
{
    /** @var string[] */
    private $middleware_list = [];

    /** @var MiddlewareBase[] */
    private $middleware_instance_list = [];

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

    public function executeCreate()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            $middleware_instance->create();
        }
    }

    public function executeBefore()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            if ($middleware_instance->before() !== true) {
                return false;
            }
        }

        return true;
    }

    public function executeAfter($content)
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            $content = $middleware_instance->after($content);
        }

        return $content;
    }

    public function executeDestroy()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            $middleware_instance->destroy();
        }
    }

    public function initializeMiddlewareList()
    {
        if (count($this->middleware_list) > 0 && count($this->middleware_instance_list) === 0) {
            foreach ($this->middleware_list as $middleware_name) {
                $instance = new $middleware_name();
                $middleware_instance_list[] = $instance;
            }
        }
    }


}