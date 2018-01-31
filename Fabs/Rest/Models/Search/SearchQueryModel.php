<?php

namespace Fabs\Rest\Models\Search;

class SearchQueryModel
{
    /** @var QueryElement[] */
    private $query_element_list = [];
    /** @var SortQueryElement */
    private $sort_query_element = null;

    /** @var int */
    private $page = 0;
    /** @var int */
    private $per_page = 0;

    /**
     * @return SortQueryElement|null
     */
    public function getSortQueryElement()
    {
        return $this->sort_query_element;
    }

    /**
     * @return QueryElement[]
     */
    public function getQueryElementList()
    {
        return $this->query_element_list;
    }

    /**
     * @param QueryElement[] $query_element_list
     * @return SearchQueryModel
     */
    public function setQueryElementList($query_element_list)
    {
        $this->query_element_list = $query_element_list;
        return $this;
    }

    /**
     * @param QueryElement $query_element
     * @return SearchQueryModel
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function addQueryElement($query_element)
    {
        $this->query_element_list[] = $query_element;
        return $this;
    }

    /**
     * @param SortQueryElement $sort_query_element
     * @return SearchQueryModel
     */
    public function setSortQueryElement($sort_query_element)
    {
        $this->sort_query_element = $sort_query_element;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->per_page;
    }

    /**
     * @param int $page
     * @return static
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param int $per_page
     * @return static
     */
    public function setPerPage($per_page)
    {
        $this->per_page = $per_page;
        return $this;
    }
}
