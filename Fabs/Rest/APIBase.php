<?php

namespace Fabs\Rest;

use Fabs\Rest\Definitions\ActionDefinition;

abstract class APIBase extends InjectableWithDefinition
{
    /** @var ActionDefinition[] */
    private $action_definition_list = [];

    /**
     * @param string $method
     * @param string $route
     * @param string|callable $definition
     * @return ActionDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineAction($method, $route, $definition)
    {
        $action_definition = new ActionDefinition();
        $action_definition->method = $method;
        $action_definition->route = $route;
        $action_definition->definition = $definition;
        if ($this->request->isMethod($method)) {
            $this->action_definition_list[] = $action_definition;
        }
        return $action_definition;
    }

    /**
     * @return ActionDefinition[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getActionDefinitionList()
    {
        return $this->action_definition_list;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();
}
