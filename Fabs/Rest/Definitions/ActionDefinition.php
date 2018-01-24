<?php


namespace Fabs\Rest\Definitions;


class ActionDefinition extends MatchableDefinitionBase
{
    /** @var string */
    public $method = null;
    /** @var string */
    public $route = null;
    /** @var string */
    public $function_name = null;
    /** @var mixed[] */
    public $parameters = [];

    /** @var string */
    private $compiled_route = null;

    /**
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getCompiledRoute()
    {
        if ($this->compiled_route === null) {
            $this->compile();
        }
        return $this->compiled_route;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function compile()
    {
        $this->compiled_route = preg_replace('/\{\w+\}/', '([a-zA-Z0-9\_]+)', $this->route);;
    }
}
