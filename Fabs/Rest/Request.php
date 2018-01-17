<?php


namespace Fabs\Rest;


class Request extends Injectable
{
    private $_GET = null;
    private $_POST = null;
    private $_SERVER = null;

    public function initialize()
    {
        $this->_GET = $_GET;
        $this->_POST = $_POST;
        $this->_SERVER = $_SERVER;
    }

    /**
     * @return string
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getURI()
    {
        if (array_key_exists('_url', $_GET)) {
            return $_GET['_url'];
        }

        return '/';
    }
}