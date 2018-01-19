<?php


namespace Fabs\Rest\Registrations;

class APIRegistration extends MatchableRegistrationBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {
            $instance = new $this->class_name();
            $this->setInstance($instance);
        }

        return $instance;
    }
}
