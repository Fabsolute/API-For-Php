<?php


namespace Fabs\Rest;
use Fabs\Rest\Http\Request;
use Fabs\Rest\Http\Response;

/**
 * Class Injectable
 * @package Fabs\Rest
 *
 * @property Router router
 * @property Request request
 * @property Response response
 * @property KernelBase kernel
 */
abstract class Injectable extends \Fabs\DI\Injectable
{
}
