<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Exceptions\StatusCodeException\BadRequestException;
use Fabs\Rest\Models\Search\SearchQueryModel;
use Fabs\Serialize\SerializableObject;
use \Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    /** @var string[] */
    private $include_list = [];
    /** @var SearchQueryModel */
    private $search_query_model = null;

    /**
     * @param SearchQueryModel $search_query_model
     * @return Request
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setSearchQueryModel($search_query_model)
    {
        $this->search_query_model = $search_query_model;
        return $this;
    }

    /**
     * @return SearchQueryModel|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getSearchQueryModel()
    {
        return $this->search_query_model;
    }

    /**
     * @param string[] $include_list
     * @return Request
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setIncludeList($include_list)
    {
        $this->include_list = $include_list;
        return $this;
    }

    /**
     * @return string[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getIncludeList()
    {
        return $this->include_list;
    }

    /**
     * @param string $include_name
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function hasInclude($include_name)
    {
        $include_name = trim(strtolower($include_name));
        return in_array($include_name, $this->getIncludeList(), true);
    }

    public function getContentAsArray()
    {
        return json_decode($this->getContent(), true);
    }

    public function getContentWithType($type)
    {
        $content = $this->getContentAsArray();
        if ($content === null) {
            throw new BadRequestException();
        }
        return SerializableObject::create($content, $type);
    }
}
