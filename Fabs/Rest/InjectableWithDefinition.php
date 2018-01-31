<?php


namespace Fabs\Rest;


use Fabs\Rest\Definitions\MiddlewareDefinitionBase;

abstract class InjectableWithDefinition extends Injectable
{
    /** @var MiddlewareDefinitionBase */
    private $definition = null;

    /**
     * @return MiddlewareDefinitionBase
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param $definition
     * @author ahmetturk <ahmetturk93@gmail.com>
     * @return InjectableWithDefinition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
        return $this;
    }
}
