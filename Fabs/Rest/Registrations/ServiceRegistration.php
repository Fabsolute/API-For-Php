<?php

namespace Fabs\Rest\Registrations;

class ServiceRegistration extends RegistrationBase
{
    /** @var string */
    private $service_name = null;
    /** @var string|callable|mixed */
    private $definition = null;
    /** @var bool */
    private $shared = false;

    /**
     * ServiceRegistration constructor.
     * @param string $service_name
     * @param string|callable|mixed $definition
     * @param bool $shared
     */
    public function __construct($service_name, $definition, $shared)
    {
        $this->service_name = $service_name;
        $this->definition = $definition;
        $this->shared = $shared;
    }

    /**
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getServiceName()
    {
        return $this->service_name;
    }

    /**
     * @return callable|mixed|string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function isShared()
    {
        return $this->shared;
    }

    /**
     * @param bool $is_shared
     * @return ServiceRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setShared($is_shared)
    {
        $this->shared = $is_shared;
        return $this;
    }

    /**
     * @param string|callable|mixed $definition
     * @return ServiceRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setDefinition($definition)
    {
        if ($this->definition != $definition) {
            $this->setInstance(null);
        }
        $this->definition = $definition;
        return $this;
    }
}