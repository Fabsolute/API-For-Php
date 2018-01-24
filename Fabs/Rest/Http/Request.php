<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Constants\Headers;
use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\Injectable;
use Fabs\Rest\Utilities\Dictionary;
use Fabs\Rest\Http\Request\HeaderDictionary;
use Fabs\Serialize\SerializableObject;

class Request extends Injectable
{
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
}
