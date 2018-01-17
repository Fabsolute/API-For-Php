<?php


namespace Fabs\Rest;


use Fabs\Rest\Models\KernelRegistration;

abstract class Application extends Injectable
{
    /** @var KernelBase[] */
    public $kernel_registration_list = [];

    protected abstract function initialize();

    public function run()
    {
        $this->initialize();

        $this->kernel_registration_list[0]->run();
    }

    protected function registerKernel($type, $class_name)
    {
        $module_registration = new KernelRegistration();
        $module_registration->type = $type;
        $module_registration->class_name = $class_name;
        $this->kernel_registration_list[] = $module_registration;
        return $module_registration;
    }
}