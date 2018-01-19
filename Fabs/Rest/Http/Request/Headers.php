<?php


namespace Fabs\Rest\Http\Request;


class Headers
{
    private $headers = [];

    public function __construct($headers = [])
    {
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    public function set($key, $values, $replace = true)
    {
        $key = str_replace('_', '-', strtolower($key));
        if (is_array($values)) {
            $values = array_values($values);

            if ($replace === true || !array_key_exists($key, $this->headers) || $this->headers[$key] === null) {
                $this->headers[$key] = $values;
            } else {
                $this->headers[$key] = array_merge($this->headers[$key], $values);
            }
        } else {
            if ($replace === true || !array_key_exists($key, $this->headers) || $this->headers[$key] === null) {
                $this->headers[$key] = [$values];
            } else {
                $this->headers[$key][] = $values;
            }
        }
    }

    public function has($key)
    {
        return array_key_exists(str_replace('_', '-', strtolower($key)), $this->headers);
    }
}