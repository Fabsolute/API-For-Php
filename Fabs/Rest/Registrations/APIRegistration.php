<?php


namespace Fabs\Rest\Registrations;

class APIRegistration extends MatchableRegistrationBase
{
    /** @var string */
    public $route = null;
    /** @var string */
    public $class_name = null;
}
