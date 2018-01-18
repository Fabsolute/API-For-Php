<?php


namespace Fabs\Rest;


use Fabs\Rest\Registrations\APIRegistration;

abstract class ModuleBase extends Injectable
{
    /** @var APIRegistration[] */
    private $api_registration_list = [];

    /**
     * ModuleBase constructor.
     * @param mixed $extra_data
     */
    public function __construct($extra_data = null)
    {
    }

    /**
     * @return APIRegistration[]
     */
    public function getAPIRegistrationList()
    {
        return $this->api_registration_list;
    }

    /**
     * @param string $route
     * @param string $class_name
     * @return APIRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function registerAPI($route, $class_name)
    {
        $api_registration = new APIRegistration();
        $api_registration->route = $route;
        $api_registration->class_name = $class_name;
        $this->api_registration_list[] = $api_registration;
        return $api_registration;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();

    /**
     * @param $dependency_injector DI
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function registerServices($dependency_injector);
}