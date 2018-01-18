<?php


namespace Fabs\Rest;


use Fabs\Rest\Registrations\KernelRegistration;

class Application extends Injectable
{
    /** @var KernelRegistration[] */
    private $kernel_registration_list = [];

    public function __construct($kernel = null, $type = null)
    {
        if ($kernel !== null) {
            $this->registerKernel($type, $kernel);
        }
    }

    protected function initialize()
    {
    }

    public function run()
    {
        $this->initialize();
        $this->request->initialize();
        $this->router->initialize();
    }

    protected function registerKernel($type, $class_name)
    {
        $kernel_registration = new KernelRegistration();
        $kernel_registration->type = $type;
        $kernel_registration->class_name = $class_name;
        $this->kernel_registration_list[] = $kernel_registration;
        return $kernel_registration;
    }

    public function getKernelRegistrationList()
    {
        return $this->kernel_registration_list;
    }
}