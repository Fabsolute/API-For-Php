<?php


namespace Fabs\Rest;


use Fabs\Rest\Definitions\ActionDefinition;
use Fabs\Rest\Definitions\APIDefinition;
use Fabs\Rest\Definitions\KernelDefinition;
use Fabs\Rest\Definitions\ModuleDefinition;

class Router extends Injectable
{
    /** @var ModuleDefinition */
    private $matched_module_definition = null;
    /** @var APIDefinition */
    private $matched_api_definition = null;
    /** @var ActionDefinition */
    private $matched_action_definition = null;
    /** @var KernelDefinition */
    private $matched_kernel_definition = null;

    /**
     * @param string $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function execute($uri = null)
    {
        if ($uri === null) {
            $uri = $this->request->getURI();
        }

        $kernel_definition_list = $this->application->getKernelDefinitionList();
        foreach ($kernel_definition_list as $kernel_definition) {
            if (
                (PHP_SAPI === 'cli' && $kernel_definition->type === PHP_SAPI) ||
                (PHP_SAPI !== 'cli' && $kernel_definition->type !== 'cli')
            ) {
                $this->kernelMatched($kernel_definition, $uri);
                return;
            }
        }
    }

    /**
     * @param KernelDefinition $kernel_definition
     * @param string $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function kernelMatched($kernel_definition, $uri)
    {
        $this->matched_kernel_definition = $kernel_definition;

        /** @var KernelBase $kernel_instance */
        $kernel_instance = $kernel_definition->getInstance();
        $kernel_instance->initialize();

        $module_definition_list = $kernel_instance->getModuleDefinitionList();
        foreach ($module_definition_list as $module_definition) {
            $new_uri = $this->match($module_definition->route, $uri);
            if ($new_uri !== false) {
                $this->moduleMatched($module_definition, $new_uri);
                return;
            }
        }
    }

    /**
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function initialize()
    {
        $this->execute();
    }

    /**
     * @return ModuleDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedModuleDefinition()
    {
        return $this->matched_module_definition;
    }

    /**
     * @return APIDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedAPIDefinition()
    {
        return $this->matched_api_definition;
    }

    /**
     * @return ActionDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedActionDefinition()
    {
        return $this->matched_action_definition;
    }

    /**
     * @return KernelDefinition
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedKernelDefinition()
    {
        return $this->matched_kernel_definition;
    }

    /**
     * @param string $route
     * @param string $uri
     * @return bool|string
     */
    private function match($route, $uri)
    {
        if ($uri !== '/') {
            $uri = rtrim($uri, " \t\n\r\0\x0B/");
        }
        $uri = preg_replace("/\/\/+/", "/", $uri);

        $route = str_replace('/', '\\/', $route);
        $regex = sprintf("/^%s(?<uri>\/.*)*$/", $route);

        if (preg_match($regex, $uri, $response) === 1) {
            if (array_key_exists('uri', $response)) {
                return $response['uri'];
            } else if (count($response) > 1) {
                foreach ($response as $key => $value) {
                    if (!is_numeric($key) || $key === 0) {
                        unset($response[$key]);
                    }
                }
                return array_values($response);
            }
            return '/';
        }
        return false;
    }

    /**
     * @param ModuleDefinition $module_definition
     * @param string|bool $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function moduleMatched($module_definition, $uri)
    {
        $this->matched_module_definition = $module_definition;

        /** @var ModuleBase $module_instance */
        $module_instance = $module_definition->getInstance();

        $module_instance->initialize($this->getDI());
        if (is_string($uri)) {
            $api_definition_list = $module_instance->getAPIDefinitionList();
            foreach ($api_definition_list as $api_definition) {
                $new_uri = $this->match($api_definition->route, $uri);
                if ($new_uri !== false) {
                    $this->apiMatched($api_definition, $new_uri);
                }
            }
        }
    }

    /**
     * @param APIDefinition $api_definition
     * @param string|bool $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function apiMatched($api_definition, $uri)
    {
        $this->matched_api_definition = $api_definition;

        /** @var APIBase $api_instance */
        $api_instance = $api_definition->getInstance();

        $api_instance->initialize();
        if (is_string($uri)) {
            $action_definition_list = $api_instance->getActionDefinitionList();
            foreach ($action_definition_list as $action_definition) {
                $new_uri = $this->match($action_definition->getCompiledRoute(), $uri);
                if ($new_uri !== false) {
                    $this->actionMatched($action_definition, $new_uri);
                }
            }
        }
    }

    /**
     * @param ActionDefinition $action_definition
     * @param string|bool|array $uri
     */
    private function actionMatched($action_definition, $uri)
    {
        $this->matched_action_definition = $action_definition;
        if (is_array($uri)) {
            $this->matched_action_definition->parameters = $uri;
        }
    }
}