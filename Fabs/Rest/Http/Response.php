<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Injectable;

class Response extends Injectable
{
    private $returned_value = null;

    public function setReturnedValue($returned_value)
    {
        $this->returned_value = $returned_value;
        return $this;
    }

    public function getReturnedValue()
    {
        return $this->returned_value;
    }
}