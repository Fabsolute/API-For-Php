<?php


namespace Fabs\Rest;


use Fabs\Rest\Definitions\DefinitionBase;

abstract class InjectableWithDefinition extends Injectable
{
    /** @var DefinitionBase */
    private $definition = null;

    /**
     * @return DefinitionBase
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
