<?php


namespace Fabs\Rest\Definitions;


use Fabs\Rest\Models\Search\QueryElement;
use Fabs\Rest\Models\Search\SortQueryElement;

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
    /** @var string[] */
    private $includable_field_list;
    /** @var QueryElement[] */
    private $query_list = [];
    /** @var SortQueryElement */
    private $default_sort_query_element = null;

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

    /**
     * @param QueryElement $query_element
     * @return ActionDefinition
     */
    public function addQueryElement($query_element)
    {
        $this->query_list[] = $query_element;
        return $this;
    }

    /**
     * @return QueryElement[]
     */
    public function getQueryElementList()
    {
        return $this->query_list;
    }

    /**
     * @return SortQueryElement
     */
    public function getDefaultSortQueryElement()
    {
        return $this->default_sort_query_element;
    }

    /**
     * @param SortQueryElement $default_sort_query_element
     * @return ActionDefinition
     */
    public function setDefaultSortQueryElement($default_sort_query_element)
    {
        $this->default_sort_query_element = $default_sort_query_element;
        return $this;
    }

    /**
     * @param string $field_name
     * @return ActionDefinition
     */
    public function addIncludableField($field_name)
    {
        $this->includable_field_list[] = $field_name;
        return $this;
    }

    /**
     * @return string[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getIncludableFieldList()
    {
        return $this->includable_field_list;
    }
}
