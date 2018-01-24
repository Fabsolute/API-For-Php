<?php


namespace Fabs\Rest\Definitions;

use Fabs\Rest\MiddlewareBase;

abstract class MiddlewareDefinitionBase extends DefinitionBase
{
    /** @var mixed[] */
    private $middleware_definition_list = [];

    /** @var MiddlewareBase[] */
    private $middleware_instance_list = [];

    /**
     * @param string $middleware
     * @param mixed[] $parameters
     * @return static
     */
    public function addMiddleware($middleware, ...$parameters)
    {
        $this->middleware_definition_list[] = ['middleware' => $middleware, 'parameters' => $parameters];
        return $this;
    }

    public function executeInitialize()
    {
        $this->initializeMiddlewareList();

        foreach ($this->middleware_instance_list as $middleware_instance) {
            $middleware_instance->initialize();
        }
    }

    public function executeBefore()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            $middleware_instance->before();
        }
    }

    public function executeAfter()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {
            $middleware_instance->after();
        }
    }

    public function executeFinalize()
    {
        foreach ($this->middleware_instance_list as $middleware_instance) {

            $middleware_instance->finalize();
        }
    }

    public function initializeMiddlewareList()
    {
        if (count($this->middleware_definition_list) > 0 && count($this->middleware_instance_list) === 0) {
            foreach ($this->middleware_definition_list as $middleware_definition) {
                $middleware_name = $middleware_definition['middleware'];
                $middleware_parameters = $middleware_definition['parameters'];
                $instance = new $middleware_name(...$middleware_parameters);
                $this->middleware_instance_list[] = $instance;
            }
        }
    }


}