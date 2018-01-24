<?php


namespace Fabs\Rest\Definitions;

use Fabs\Rest\InjectableWithDefinition;

class APIDefinition extends MatchableDefinitionBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {

            /** @var InjectableWithDefinition $instance */
            $instance = new $this->class_name();
            $instance->setDefinition($this);

            $this->setInstance($instance);
        }

        return $instance;
    }
}
