<?php


namespace Fabs\Rest;


use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\DI\FactoryDefault;
use Fabs\Rest\Exceptions\StatusCodeException\MethodNotAllowedException;
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

    /**
     * @param string|callable|null $kernel
     * @param string|null $type
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
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

    /**
     * @param string $type
     * @param string|callable $definition
     * @return KernelDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineKernel($type, $definition)
    {
        $kernel_definition = new KernelDefinition();
        $kernel_definition->type = $type;
        $kernel_definition->setDefinition($definition);
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

        // create
        if ($kernel_definition !== null) {
            $kernel_definition->executeInitialize();
        }
        if ($module_definition !== null) {
            $module_definition->executeInitialize();
        }
        if ($api_definition !== null) {
            $api_definition->executeInitialize();
        }
        if ($action_definition !== null) {
            $action_definition->executeInitialize();
        }

        try {
            if ($kernel_definition === null ||
                $module_definition === null ||
                $api_definition === null ||
                $action_definition === null ||
                (
                    !is_callable($action_definition->getDefinition()) &&
                    !is_callable(
                        [
                            $api_definition->getInstance(),
                            $action_definition->getDefinition()
                        ]
                    ))) {
                if ($this->router->isActionMatched()) {
                    if (!$this->request->isMethod(HttpMethods::OPTIONS) || !$this->router->isAutoAllowOptionsMethod()) {
                        throw new MethodNotAllowedException();
                    }
                } else {
                    throw new NotFoundException();
                }
            }


            // before
            $kernel_definition->executeBefore();
            $module_definition->executeBefore();
            $api_definition->executeBefore();

            if ($action_definition !== null) {
                $action_definition->executeBefore();

                // execution
                if (is_callable($action_definition->getDefinition())) {
                    $returned_value = call_user_func_array(
                        $action_definition->getDefinition(),
                        $action_definition->parameters
                    );
                } else {
                    $returned_value = call_user_func_array(
                        [
                            $api_definition->getInstance(),
                            $action_definition->getDefinition()
                        ],
                        $action_definition->parameters
                    );
                }

                $this->response->setReturnedValue($returned_value);

                // after
                $action_definition->executeAfter();
            }

            $api_definition->executeAfter();
            $module_definition->executeAfter();
            $kernel_definition->executeAfter();

            // destroy
            if ($action_definition !== null) {
                $action_definition->executeFinalize();
            }

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
