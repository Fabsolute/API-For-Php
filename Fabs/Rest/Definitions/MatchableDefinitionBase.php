<?php


namespace Fabs\Rest\Definitions;

abstract class MatchableDefinitionBase extends MiddlewareDefinitionBase
{
    private $is_default = false;

    /**
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function isDefault()
    {
        return $this->is_default;
    }

    /**
     * @param bool $is_default
     * @return static
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setDefault($is_default = true)
    {
        $this->is_default = $is_default;
        return $this;
    }
}
