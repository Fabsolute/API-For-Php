<?php


namespace Fabs\Rest;


use Fabs\Rest\DI\FactoryDefault;
use Fabs\Rest\Exceptions\StatusCodeException\NotFoundException;
use Fabs\Rest\Definitions\KernelDefinition;

class Application extends Injectable
{
    /** @var KernelDefinition[] */
    private $kernel_definition_list = [];

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
        set_error_handler(function ($error_no, $error_message, $error_file, $error_line) {
            throw new \ErrorException($error_message, 0, $error_no, $error_file, $error_line);
        });

        if ($kernel !== null) {
            $this->defineKernel($type, $kernel);
        }

        $this->initialize();

        $this->request->initialize();
        $this->router->initialize();

        $this->execute();
    }

    protected function defineKernel($type, $class_name)
    {
        $kernel_definition = new KernelDefinition();
        $kernel_definition->type = $type;
        $kernel_definition->class_name = $class_name;
        $this->kernel_definition_list[] = $kernel_definition;
        return $kernel_definition;
    }

    public function getKernelDefinitionList()
    {
        return $this->kernel_definition_list;
    }

    private function execute()
    {
        $kernel_definition = $this->router->getMatchedKernelDefinition();
        $module_definition = $this->router->getMatchedModuleDefinition();
        $api_definition = $this->router->getMatchedAPIDefinition();
        $action_definition = $this->router->getMatchedActionDefinition();

        try {
            if ($kernel_definition === null ||
                $module_definition === null ||
                $api_definition === null ||
                $action_definition === null ||
                !is_callable([
                    $api_definition->getInstance(),
                    $action_definition->function_name
                ])) {
                throw new NotFoundException();
            }

            // create
            $kernel_definition->executeInitialize();
            $module_definition->executeInitialize();
            $api_definition->executeInitialize();
            $action_definition->executeInitialize();

            // before
            $kernel_definition->executeBefore();
            $module_definition->executeBefore();
            $api_definition->executeBefore();
            $action_definition->executeBefore();

            // execution
            $returned_value = call_user_func_array(
                [
                    $api_definition->getInstance(),
                    $action_definition->function_name
                ],
                $action_definition->parameters
            );

            $this->response->setReturnedValue($returned_value);

            // after
            $action_definition->executeAfter();
            $api_definition->executeAfter();
            $module_definition->executeAfter();
            $kernel_definition->executeAfter();

            // destroy
            $action_definition->executeFinalize();
            $api_definition->executeFinalize();
            $module_definition->executeFinalize();
            $kernel_definition->executeFinalize();
        } catch (\Exception $exception) {
            if ($kernel_definition !== null) {
                $kernel_definition->handleException($exception);
            } else {
                throw $exception;
            }
        }
    }
}
