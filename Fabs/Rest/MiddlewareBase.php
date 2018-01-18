<?php


namespace Fabs\Rest;


abstract class MiddlewareBase extends Injectable
{
    public function create()
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

    public function destroy()
    {

    }
}
