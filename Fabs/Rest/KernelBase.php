<?php


namespace Fabs\Rest;

use Fabs\Rest\Definitions\KernelDefinition;
use Fabs\Rest\Definitions\ModuleDefinition;

abstract class KernelBase extends InjectableWithDefinition
{
    /** @var ModuleDefinition[] */
    private $module_definition_list = [];

    /**
     * @param string $route
     * @param string $class_name
     * @param mixed $extra_data
     * @return ModuleDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineModule($route, $class_name, $extra_data = null)
    {
        $module_definition = new ModuleDefinition();
        $module_definition->route = $route;
        $module_definition->class_name = $class_name;
        $module_definition->extra_data = $extra_data;
        $this->module_definition_list[] = $module_definition;
        return $module_definition;
    }

    /**
     * @return ModuleDefinition[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getModuleDefinitionList()
    {
        return $this->module_definition_list;
    }

    /**
     * @return KernelDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getDefinition()
    {
        /** @var KernelDefinition $definition */
        $definition = parent::getDefinition();
        return $definition;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();
}