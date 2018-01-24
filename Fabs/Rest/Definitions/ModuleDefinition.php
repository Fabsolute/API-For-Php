<?php

namespace Fabs\Rest\Definitions;

use Fabs\Rest\InjectableWithDefinition;

class ModuleDefinition extends MatchableDefinitionBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;
    /** @var mixed|null */
    public $extra_data = null;

    public function getInstance()
    {
        $instance = parent::getInstance();

        if ($instance === null) {

            /** @var InjectableWithDefinition $instance */
            $instance = new $this->class_name($this->extra_data);
            $instance->setDefinition($this);

            $this->setInstance($instance);
        }

        return $instance;
    }
}
