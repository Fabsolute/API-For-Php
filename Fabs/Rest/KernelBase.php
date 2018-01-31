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
     * @param string|callable $definition
     * @return ModuleDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineModule($route, $definition)
    {
        $module_definition = new ModuleDefinition();
        $module_definition->route = $route;
        $module_definition->definition = $definition;
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