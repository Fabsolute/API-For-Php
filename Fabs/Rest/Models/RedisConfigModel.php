<?php


namespace Fabs\Rest\Models;


use Fabs\Serialize\SerializableObject;

class RedisConfigModel extends SerializableObject
{
    /** @var string */
    public $host = "localhost";
    /** @var int */
    public $port = 6379;
    /** @var bool */
    public $persistent = false;
    /** @var string */
    public $auth = null;
    /** @var int */
    public $index = 0;
    /** @var string */
    public $prefix = null;
    /** @var int */
    public $lifetime = 0;
}