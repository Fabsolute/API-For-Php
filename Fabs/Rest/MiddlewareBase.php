<?php


namespace Fabs\Rest;


abstract class MiddlewareBase extends Injectable
{
    public function initialize()
    {
    }

    public function before()
    {
        return true;
    }

    public function after($content)
    {
        return $content;
    }

    public function finalize()
    {

    }
}
