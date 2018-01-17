<?php


namespace Fabs\Rest;


abstract class MiddlewareBase extends Injectable
{
    public abstract function handle();

    public function finished()
    {

    }
}
