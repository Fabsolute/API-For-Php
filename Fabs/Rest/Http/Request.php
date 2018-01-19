<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Injectable;
use Fabs\Rest\Utilities\Dictionary;
use Fabs\Rest\Http\Request\HeaderDictionary;

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
            $this->http_method = strtoupper($this->http_server->get('REQUEST_METHOD', 'GET'));
            if ('POST' === $this->http_method) {
                if ($method = $this->headers->get('X-HTTP-METHOD-OVERRIDE')) {
                    $this->http_method = strtoupper($method);
                }
            }
        }

        return $this->http_method;
    }
}
