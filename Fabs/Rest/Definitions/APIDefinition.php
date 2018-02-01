<?php


namespace Fabs\Rest\Definitions;

use Fabs\Rest\InjectableWithDefinition;

class APIDefinition extends MatchableDefinitionBase
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
            if (is_callable($this->definition) === true) {
                $instance = new $this->definition();
            } else {
                $instance = call_user_func($this->definition);
            }

            $instance->setDefinition($this);
            $this->setInstance($instance);
        }

        return $instance;
    }
}
