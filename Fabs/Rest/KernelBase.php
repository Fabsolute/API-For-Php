<?php


namespace Fabs\Rest;

use Fabs\Rest\Registrations\ModuleRegistration;

abstract class KernelBase extends Injectable
{

    /** @var ModuleRegistration[] */
    private $module_registration_list = [];

    /**
     * @param string $route
     * @param string $class_name
     * @param mixed $extra_data
     * @return ModuleRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function registerModule($route, $class_name, $extra_data = null)
    {
        $module_registration = new ModuleRegistration();
        $module_registration->route = $route;
        $module_registration->class_name = $class_name;
        $module_registration->extra_data = $extra_data;
        $this->module_registration_list[] = $module_registration;
        return $module_registration;
    }

    /**
     * @return ModuleRegistration[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getModuleRegistrationList()
    {
        return $this->module_registration_list;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();
}