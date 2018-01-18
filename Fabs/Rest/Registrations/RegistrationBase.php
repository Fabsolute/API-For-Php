<?php


namespace Fabs\Rest\Registrations;


abstract class RegistrationBase
{
    /** @var mixed */
    private $instance = null;

    /**
     * @return mixed
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param mixed $instance
     * @return static
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }
}
