<?php

namespace Fabs\Rest\Registrations;

class ModuleRegistration extends MatchableRegistrationBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;
    /** @var mixed|null */
    public $extra_data = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {
            $instance = new $this->class_name($this->extra_data);
            $this->setInstance($instance);
        }

        return $instance;
    }
}
