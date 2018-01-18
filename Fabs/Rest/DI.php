<?php


namespace Fabs\Rest;


use Fabs\Rest\Registrations\ServiceRegistration;

class DI implements \ArrayAccess
{
    /** @var DI */
    private static $default_dependency_injector = null;
    /**
     * @var ServiceRegistration[]
     */
    protected $service_lookup = [];

    /**
     * @return DI
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public static function getDefault()
    {
        if (self::$default_dependency_injector === null) {
            self::$default_dependency_injector = new DI();
        }

        return self::$default_dependency_injector;
    }

    /**
     * @param DI $dependency_injector
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public static function setDefault($dependency_injector)
    {
        self::$default_dependency_injector = $dependency_injector;
    }

    /**
     * @param string $service_name
     * @param string|callable|mixed $definition
     * @param bool $shared
     * @return ServiceRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function set($service_name, $definition, $shared = false)
    {
        $service_registration = new ServiceRegistration($service_name, $definition, $shared);
        $this->service_lookup[$service_name] = $service_registration;
        return $service_registration;
    }

    /**
     * @param string $service_name
     * @return ServiceRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getService($service_name)
    {
        if (array_key_exists($service_name, $this->service_lookup)) {
            return $this->service_lookup[$service_name];
        }

        return null;
    }

    /**
     * @param string $service_name
     * @return mixed|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function get($service_name)
    {
        $service = $this->getService($service_name);
        if ($service === null) {
            // todo throw
            return null;
        }

        return $this->resolve($service);
    }

    /**
     * @param ServiceRegistration $service
     * @return mixed|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function resolve($service)
    {
        if ($service->isShared()) {
            if ($service->getInstance() !== null) {
                return $service->getInstance();
            }
        }

        $instance = null;
        $definition = $service->getDefinition();

        if (is_string($definition)) {
            if (class_exists($definition)) {
                $instance = new $definition;
            }
        } else {
            if (is_callable($definition)) {
                $instance = call_user_func($definition);
            } else {
                $instance = $definition;
            }
        }

        if ($service->isShared()) {
            $service->setInstance($instance);
        }

        if ($instance instanceof Injectable) {
            $instance->setDI($this);
        }

        return $instance;
    }

    /**
     * @param string $service_name
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function has($service_name)
    {
        return array_key_exists($service_name, $this->service_lookup);
    }

    /**
     * @param string $service_name
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function offsetExists($service_name)
    {
        return $this->has($service_name);
    }

    /**
     * @param string $service_name
     * @return mixed|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function offsetGet($service_name)
    {
        return $this->get($service_name);
    }

    /**
     * @param string $service_name
     * @param string|callable|mixed $definition
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function offsetSet($service_name, $definition)
    {
        $this->set($service_name, $definition, true);
        return true;
    }

    /**
     * @param string $service_name
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function offsetUnset($service_name)
    {
        return false;
    }
}
