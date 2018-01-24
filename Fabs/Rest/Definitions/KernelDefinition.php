<?php


namespace Fabs\Rest\Definitions;


use Fabs\Rest\InjectableWithDefinition;

class KernelDefinition extends MatchableDefinitionBase
{
    /** @var string */
    public $type = null;
    /** @var string */
    public $class_name = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {

            /** @var InjectableWithDefinition $instance */
            $instance = new $this->class_name;
            $instance->setDefinition($this);

            $this->setInstance($instance);
        }

        return $instance;
    }
}
