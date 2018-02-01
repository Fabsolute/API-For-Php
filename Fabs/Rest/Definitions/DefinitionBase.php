<?php


namespace Fabs\Rest\Definitions;


use Fabs\Rest\InjectableWithDefinition;

abstract class DefinitionBase
{
    /** @var mixed */
    private $instance = null;
    /** @var string|callable */
    private $definition = null;

    /**
     * @return mixed
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getInstance()
    {
        if ($this->instance === null) {

            if (is_callable($this->getDefinition())) {
                $instance = call_user_func($this->getDefinition());
            } else {
                $definition = $this->getDefinition();
                $instance = new $definition;
            }

            if ($instance instanceof InjectableWithDefinition) {
                $instance->setDefinition($this);
            }

            $this->setInstance($instance);
        }

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

    /**
     * @return callable|mixed|string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param string|callable $definition
     * @return static
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }
}
