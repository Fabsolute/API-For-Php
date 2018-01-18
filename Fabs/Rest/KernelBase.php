<?php


namespace Fabs\Rest;


use Fabs\Rest\DI\FactoryDefault;
use Fabs\Rest\Registrations\ModuleRegistration;

abstract class KernelBase extends Injectable
{

    /** @var ModuleRegistration[] */
    private $module_registration_list = [];

    /**
     * Application constructor.
     * @param DI $dependency_injector
     */
    public function __construct($dependency_injector = null)
    {
        if ($dependency_injector === null) {
            $dependency_injector = new FactoryDefault();
            DI::setDefault($dependency_injector);
        }

        $dependency_injector->set('application', $this);
    }

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
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function run()
    {
        $this->initialize();
        $this->request->initialize();
        $this->router->execute();

        echo call_user_func_array(
            [
                $this->router->getMatchedAPIRegistration()->getInstance(),
                $this->router->getMatchedActionRegistration()->function_name
            ],
            $this->router->getMatchedActionRegistration()->parameters
        );
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
    protected abstract function initialize();
}