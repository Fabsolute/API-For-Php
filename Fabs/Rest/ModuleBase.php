<?php


namespace Fabs\Rest;


use Fabs\Rest\Definitions\APIDefinition;

abstract class ModuleBase extends InjectableWithDefinition
{
    /** @var APIDefinition[] */
    private $api_definition_list = [];

    /**
     * ModuleBase constructor.
     * @param mixed $extra_data
     */
    public function __construct($extra_data = null)
    {
    }

    /**
     * @return APIDefinition[]
     */
    public function getAPIDefinitionList()
    {
        return $this->api_definition_list;
    }

    /**
     * @param string $route
     * @param string $class_name
     * @return APIDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineAPI($route, $class_name)
    {
        $api_definition = new APIDefinition();
        $api_definition->route = $route;
        $api_definition->class_name = $class_name;
        $this->api_definition_list[] = $api_definition;
        return $api_definition;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();

    /**
     * @param $dependency_injector DI
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function defineServices($dependency_injector);
}
