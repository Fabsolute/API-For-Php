<?php

namespace Fabs\Rest\Definitions;

use Fabs\Rest\InjectableWithDefinition;

class ModuleDefinition extends MatchableDefinitionBase
{
    /** @var string */
    public $route = null;
    /** @var string|callable */
    public $definition = null;

    public function getInstance()
    {
        /** @var InjectableWithDefinition $instance */
        $instance = parent::getInstance();

        if ($instance === null) {
            if (is_callable($this->definition)) {
                $instance = call_user_func($this->definition);
            } else {
                $instance = new $this->definition();
            }

            $instance->setDefinition($this);
            $this->setInstance($instance);
        }

        return $instance;
    }
}
