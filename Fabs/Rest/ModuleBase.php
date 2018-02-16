<?php


namespace Fabs\Rest;


use Fabs\DI\Container;
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
        /** @var APIDefinition $api_definition */
        $api_definition = $this->getContainer()->createInstance(APIDefinition::class);
        $api_definition->route = $route;
        $api_definition->setDefinition($definition);
        $this->api_definition_list[] = $api_definition;
        return $api_definition;
    }

    /**
     * @param $container Container
     */
    public abstract function initialize($container);
}
