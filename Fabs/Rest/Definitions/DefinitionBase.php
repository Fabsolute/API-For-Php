<?php


namespace Fabs\Rest\Definitions;


use Fabs\Rest\Injectable;

abstract class DefinitionBase extends Injectable
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
            $instance = $this->getContainer()->createInstance($this->getDefinition());

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
