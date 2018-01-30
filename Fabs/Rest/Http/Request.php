<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Constants\Headers;
use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\Injectable;
use Fabs\Rest\Models\Search\SearchQueryModel;
use Fabs\Rest\Utilities\Dictionary;
use Fabs\Rest\Http\Request\HeaderDictionary;
use Fabs\Serialize\SerializableObject;

class Request extends Injectable
{
    /** @var string[] */
    private $include_list = [];
    /** @var Dictionary */
    private $http_get = null;
    /** @var Dictionary */
    private $http_post = null;
    /** @var Dictionary */
    private $http_server = null;
    /** @var HeaderDictionary */
    private $headers = null;
    /** @var string */
    private $raw_body = null;
    /** @var SearchQueryModel */
    private $search_query_model = null;

    /** @var string */
    private $http_method = null;

    public function initialize()
    {
        $this->http_get = new Dictionary($_GET);
        $this->http_post = new Dictionary($_POST);
        $this->http_server = new Dictionary($_SERVER);
        $this->headers = new HeaderDictionary($_SERVER);
    }

    /**
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getURI()
    {
        if ($this->http_get !== null) {
            if ($this->http_get->has('_url')) {
                return $this->http_get->get('_url');
            }
        }
        return '/';
    }

    /**
     * @param string $method_name
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function isMethod($method_name)
    {
        $method = $this->getMethod();
        if (strtoupper($method_name) === $method) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMethod()
    {
        if ($this->http_method === null) {
            $this->http_method = strtoupper($this->http_server->get(Headers::REQUEST_METHOD, HttpMethods::GET));
            if ($this->http_method === HttpMethods::POST) {
                if ($method = $this->headers->get(Headers::X_HTTP_METHOD_OVERRIDE)) {
                    $this->http_method = strtoupper($method);
                }
            }
        }

        return $this->http_method;
    }

    /**
     * @return bool|string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getRawBody()
    {
        if ($this->raw_body !== null) {
            $this->raw_body = file_get_contents('php://input');
        }

        return $this->raw_body;
    }

    /**
     * @param bool $associative
     * @return bool|array|\stdClass
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getJsonRawBody($associative = false)
    {
        $raw_body = $this->getRawBody();

        if (!is_string($raw_body)) {
            return false;
        }

        return json_decode($raw_body, $associative);
    }

    /**
     * @return array
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getArrayBody()
    {
        $body = $this->getJsonRawBody(true);
        if (is_array($body)) {
            return $body;
        }
        return [];
    }

    /**
     * @param string $type
     * @param bool $is_array
     * @return SerializableObject|SerializableObject[]|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getBodyWithType($type, $is_array = false)
    {
        $raw_json_body = $this->getArrayBody();
        if ($is_array === true) {

            $response = [];
            foreach ($raw_json_body as $value) {
                $response[] = SerializableObject::create($value, $type);
            }

            return $response;
        }
        return SerializableObject::create($raw_json_body, $type);
    }

    /**
     * @param string $header_name
     * @return mixed
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getHeader($header_name)
    {
        return $this->headers->get($header_name);
    }

    public function getClientAddress()
    {
        $ip = $this->http_server->get('REMOTE_ADDR');
        if ($ip !== null) {
            if (strpos($ip, ',') !== -1) {
                $ip_parts = explode(',', $ip);
                $ip = $ip_parts[0];
            }
            return $ip;
        }
        return null;
    }

    /**
     * @param string $key
     * @param string|null $default_value
     * @return string|null
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getQuery($key, $default_value = null)
    {
        return $this->http_get->get($key, $default_value);
    }

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
}
