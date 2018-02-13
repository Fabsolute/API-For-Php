<?php


namespace Fabs\Rest;

use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\Constants\KernelTypes;
use Fabs\Rest\Definitions\KernelDefinition;
use Fabs\Rest\Definitions\ModuleDefinition;
use Fabs\Rest\DI\FactoryDefault;
use Fabs\Rest\Exceptions\StatusCodeException\MethodNotAllowedException;
use Fabs\Rest\Exceptions\StatusCodeException\NotFoundException;

abstract class KernelBase extends InjectableWithDefinition
{
    /** @var ModuleDefinition[] */
    private $module_definition_list = [];
    /** @var string[] */
    private $exception_handler_list = [];
    /** @var int */
    private $exception_depth = 0;
    /** @var int */
    protected static $MAXIMUM_EXCEPTION_DEPTH = 5;

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

        $dependency_injector->setShared('kernel', $this);
    }

    /**
     * @param string $route
     * @param string|callable $definition
     * @return ModuleDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    protected function defineModule($route, $definition)
    {
        $module_definition = new ModuleDefinition();
        $module_definition->route = $route;
        $module_definition->setDefinition($definition);
        $this->module_definition_list[] = $module_definition;
        return $module_definition;
    }

    /**
     * @return ModuleDefinition[]
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getModuleDefinitionList()
    {
        return $this->module_definition_list;
    }

    /**
     * @return KernelDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getDefinition()
    {
        /** @var KernelDefinition $definition */
        $definition = parent::getDefinition();
        if ($definition === null) {
            $definition = new KernelDefinition();
            $definition->type = KernelTypes::WEB;
            $definition->setDefinition(static::class);
            $definition->setInstance($this);
            $this->setDefinition($definition);
        }
        return $definition;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public abstract function initialize();


    /**
     * @param string $exception_class
     * @param string $handler_class
     * @return KernelBase
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setExceptionHandler($exception_class, $handler_class)
    {
        $this->exception_handler_list[$exception_class] = $handler_class;
        return $this;
    }

    public function handleException($exception)
    {
        try {
            foreach ($this->exception_handler_list as $exception_class => $handler_class) {
                if ($exception instanceof $exception_class) {
                    /** @var ExceptionHandlerBase $handler */
                    $handler = new $handler_class();
                    $handler->handle($exception);
                    return;
                }
            }
        } catch (\Exception $sub_exception) {
            if ($this->exception_depth < static::$MAXIMUM_EXCEPTION_DEPTH) {
                $this->handleException($sub_exception);
                $this->exception_depth++;
                return;
            }
            $exception = $sub_exception;
        }

        throw $exception;
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function run()
    {
        set_error_handler(function ($error_no, $error_message, $error_file, $error_line) {
            throw new \ErrorException($error_message, 0, $error_no, $error_file, $error_line);
        });

        set_exception_handler([$this, 'handleException']);


        $this->initialize();

        $this->router->initialize();

        $this->execute();
    }


    private function execute()
    {
        $kernel_definition = $this->getDefinition();
        $module_definition = $this->router->getMatchedModuleDefinition();
        $api_definition = $this->router->getMatchedAPIDefinition();
        $action_definition = $this->router->getMatchedActionDefinition();

        // create
        $kernel_definition->executeInitialize();
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
            if ($module_definition === null ||
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
            $this->handleException($exception);
        }
    }
}