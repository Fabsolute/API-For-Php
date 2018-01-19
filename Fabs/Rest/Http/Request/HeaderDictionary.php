<?php


namespace Fabs\Rest\Http\Request;


use Fabs\Rest\Utilities\Dictionary;

class HeaderDictionary extends Dictionary
{
    protected function prepareKey($key)
    {
        $key = strtoupper(strtr($key, '-', '_'));
        return $key;
    }

    public function get($key, $default = null)
    {
        $response = parent::get($key, $default);
        if ($response === $default && strpos($key, 'HTTP_') !== 0) {
            $response = parent::get('HTTP_' . $key, $default);
        }
        return $response;
    }

    public function has($key)
    {
        $exists = parent::has($key);

        if (!$exists && strpos($key, 'HTTP_') !== 0) {
            $exists = parent::has('HTTP_' . $key);
        }

        return $exists;
    }
}