<?php

namespace Fabs\Rest;

abstract class ServiceFactoryBase extends Injectable
{
    public abstract function create($parameters = []);
}
