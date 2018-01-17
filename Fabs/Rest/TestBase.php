<?php


namespace Fabs\Rest;


class TestBase
{
    private function transformHeadersToServerVars(array $headers)
    {
        $server = [];
        $prefix = 'HTTP_';
        foreach ($headers as $name => $value) {
            $name = strtr(strtoupper($name), '-', '_');
            if (!$this->startWith($name, $prefix) && $name != 'CONTENT_TYPE') {
                $name = $prefix . $name;
            }
            $server[$name] = $value;
        }
        return $server;
    }

    private function startWith($haystack, $needle)
    {
        return ((FALSE !== strpos($haystack, $needle)) &&
            (0 == strpos($haystack, $needle)));
    }
}