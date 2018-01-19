<?php


namespace Fabs\Rest;


use Fabs\Rest\Registrations\ActionRegistration;
use Fabs\Rest\Registrations\APIRegistration;
use Fabs\Rest\Registrations\KernelRegistration;
use Fabs\Rest\Registrations\ModuleRegistration;

class Router extends Injectable
{
    /** @var ModuleRegistration */
    private $matched_module_registration = null;
    /** @var APIRegistration */
    private $matched_api_registration = null;
    /** @var ActionRegistration */
    private $matched_action_registration = null;
    /** @var KernelRegistration */
    private $matched_kernel_registration = null;

    /**
     * @param string $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function execute($uri = null)
    {
        if ($uri === null) {
            $uri = $this->request->getURI();
        }

        $kernel_registration_list = $this->application->getKernelRegistrationList();
        foreach ($kernel_registration_list as $kernel_registration) {
            if (
                (PHP_SAPI === 'cli' && $kernel_registration->type === PHP_SAPI) ||
                (PHP_SAPI !== 'cli' && $kernel_registration->type !== 'cli')
            ) {
                $this->kernelMatched($kernel_registration, $uri);
                return;
            }
        }
    }

    /**
     * @param KernelRegistration $kernel_registration
     * @param string $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function kernelMatched($kernel_registration, $uri)
    {
        $this->matched_kernel_registration = $kernel_registration;

        /** @var KernelBase $kernel_instance */
        $kernel_instance = $kernel_registration->getInstance();
        $kernel_instance->initialize();

        $module_registration_list = $kernel_instance->getModuleRegistrationList();
        foreach ($module_registration_list as $module_registration) {
            $new_uri = $this->match($module_registration->route, $uri);
            if ($new_uri !== false) {
                $this->moduleMatched($module_registration, $new_uri);
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
     * @return ModuleRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedModuleRegistration()
    {
        return $this->matched_module_registration;
    }

    /**
     * @return APIRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedAPIRegistration()
    {
        return $this->matched_api_registration;
    }

    /**
     * @return ActionRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedActionRegistration()
    {
        return $this->matched_action_registration;
    }

    /**
     * @return KernelRegistration
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function getMatchedKernelRegistration()
    {
        return $this->matched_kernel_registration;
    }

    /**
     * @param string $route
     * @param string $uri
     * @return bool|string
     */
    private function match($route, $uri)
    {
        $uri = rtrim($uri, " \t\n\r\0\x0B/");
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
            return true;
        }
        return false;
    }

    /**
     * @param ModuleRegistration $module_registration
     * @param string|bool $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function moduleMatched($module_registration, $uri)
    {
        $this->matched_module_registration = $module_registration;

        /** @var ModuleBase $module_instance */
        $module_instance = $module_registration->getInstance();

        $module_instance->initialize();
        $module_instance->registerServices($this->getDI());
        if (is_string($uri)) {
            $api_registration_list = $module_instance->getAPIRegistrationList();
            foreach ($api_registration_list as $api_registration) {
                $new_uri = $this->match($api_registration->route, $uri);
                if ($new_uri !== false) {
                    $this->apiMatched($api_registration, $new_uri);
                }
            }
        }
    }

    /**
     * @param APIRegistration $api_registration
     * @param string|bool $uri
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    private function apiMatched($api_registration, $uri)
    {
        $this->matched_api_registration = $api_registration;

        /** @var APIBase $api_instance */
        $api_instance = $api_registration->getInstance();

        $api_instance->initialize();
        if (is_string($uri)) {
            $action_registration_list = $api_instance->getActionRegistrationList();
            foreach ($action_registration_list as $action_registration) {
                $new_uri = $this->match($action_registration->getCompiledRoute(), $uri);
                if ($new_uri !== false) {
                    $this->actionMatched($action_registration, $new_uri);
                }
            }
        }
    }

    /**
     * @param ActionRegistration $action_registration
     * @param string|bool|array $uri
     */
    private function actionMatched($action_registration, $uri)
    {
        $this->matched_action_registration = $action_registration;
        if (is_array($uri)) {
            $this->matched_action_registration->parameters = $uri;
        }
    }
}