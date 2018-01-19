<?php


namespace Fabs\Rest\Registrations;


class KernelRegistration extends MatchableRegistrationBase
{
    /** @var string */
    public $type = null;
    /** @var string */
    public $class_name = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {
            $instance = new $this->class_name;
            $this->setInstance($instance);
        }

        return $instance;
    }
}
