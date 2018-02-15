<?php


namespace Fabs\Rest;


use Fabs\DI\DI;
use Fabs\Rest\Definitions\APIDefinition;

abstract class ModuleBase extends InjectableWithDefinition
{
    /** @var APIDefinition[] */
    private $api_definition_list = [];

    /**
     * @return APIDefinition[]
     */
    public function getAPIDefinitionList()
    {
        return $this->api_definition_list;
    }

    /**
     * @param string $route
     * @param string|callable $definition
     * @return APIDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineAPI($route, $definition)
    {
        $api_definition = new APIDefinition();
        $api_definition->route = $route;
        $api_definition->setDefinition( $definition);
        $this->api_definition_list[] = $api_definition;
        return $api_definition;
    }

    /**
     * @param $dependency_injector DI
     */
    public abstract function initialize($dependency_injector);
}
