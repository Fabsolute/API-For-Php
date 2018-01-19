<?php


namespace Fabs\Rest;


use Fabs\Rest\DI\FactoryDefault;
use Fabs\Rest\Exceptions\NotFoundException;
use Fabs\Rest\Registrations\KernelRegistration;

class Application extends Injectable
{
    /** @var KernelRegistration[] */
    private $kernel_registration_list = [];

    /**
     * Application constructor.
     * @param DI $dependency_injector
     */
    public function __construct($dependency_injector = null)
    {
        if ($dependency_injector === null) {
            $dependency_injector = new FactoryDefault();
            DI::setDefault($dependency_injector);
        }

        $dependency_injector->set('application', $this);
    }

    protected function initialize()
    {
    }

    public function run($kernel = null, $type = null)
    {
        if ($kernel !== null) {
            $this->registerKernel($type, $kernel);
        }

        $this->initialize();

        $this->request->initialize();
        $this->router->initialize();

        $this->execute();
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

    private function execute()
    {
        $kernel_registration = $this->router->getMatchedKernelRegistration();
        $module_registration = $this->router->getMatchedModuleRegistration();
        $api_registration = $this->router->getMatchedAPIRegistration();
        $action_registration = $this->router->getMatchedActionRegistration();

        if ($kernel_registration === null ||
            $module_registration === null ||
            $api_registration === null ||
            $action_registration === null ||
            !is_callable([
                $api_registration->getInstance(),
                $action_registration->function_name
            ])) {
            throw new NotFoundException();
        }

        // create
        $kernel_registration->executeInitialize();
        $module_registration->executeInitialize();
        $api_registration->executeInitialize();
        $action_registration->executeInitialize();

        // before
        $kernel_registration->executeBefore();
        $module_registration->executeBefore();
        $api_registration->executeBefore();
        $action_registration->executeBefore();

        // execution
        $returned_value = call_user_func_array(
            [
                $api_registration->getInstance(),
                $action_registration->function_name
            ],
            $action_registration->parameters
        );

        // after
        $returned_value = $action_registration->executeAfter($returned_value);
        $returned_value = $api_registration->executeAfter($returned_value);
        $returned_value = $module_registration->executeAfter($returned_value);
        $returned_value = $kernel_registration->executeAfter($returned_value);
        $this->response->setReturnedValue($returned_value);


        // destroy
        $action_registration->executeFinalize();
        $api_registration->executeFinalize();
        $module_registration->executeFinalize();
        $kernel_registration->executeFinalize();
    }
}
