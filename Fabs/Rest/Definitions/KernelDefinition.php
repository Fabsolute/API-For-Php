<?php


namespace Fabs\Rest\Definitions;


use Fabs\Rest\ExceptionHandlerBase;

class KernelDefinition extends MiddlewareDefinitionBase
{
    /** @var string */
    public $type = null;
    /** @var string[] */
    private $exception_handler_list = [];
    /** @var int */
    private $exception_depth = 0;
    /** @var int */
    public static $MAXIMUM_EXCEPTION_DEPTH = 5;

    /**
     * @param string $exception_class
     * @param string $handler_class
     * @return KernelDefinition
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
            if ($this->exception_depth < self::$MAXIMUM_EXCEPTION_DEPTH) {
                $this->handleException($sub_exception);
                $this->exception_depth++;
                return;
            }
            $exception = $sub_exception;
        }

        throw $exception;
    }
}
