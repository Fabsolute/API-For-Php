<?php

namespace Fabs\Rest\Models;

class ModuleRegistration extends RegistrationBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;
    /** @var mixed|null */
    public $extra_data = null;
}
