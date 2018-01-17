<?php

namespace Fabs\Rest;

use Fabs\Rest\Models\ActionRegistration;

abstract class APIBase extends Injectable
{
    /** @var ActionRegistration[] */
    private $action_registration_list = [];

    /**
     * @param string $method
     * @param string $route
     * @param string $function_name
     * @return ActionRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function registerAction($method, $route, $function_name)
    {
        $action_registration = new ActionRegistration();
        $action_registration->method = $method;
        $action_registration->route = $route;
        $action_registration->function_name = $function_name;
        $this->action_registration_list[] = $action_registration;
        return $action_registration;
    }

    /**
     * @return ActionRegistration[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getActionRegistrationList()
    {
        return $this->action_registration_list;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();
}
