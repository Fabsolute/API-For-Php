<?php


namespace Fabs\Rest\Middlewares;


use Fabs\LINQ\LINQ;
use Fabs\Rest\MiddlewareBase;

class IncludeMiddleware extends MiddlewareBase
{
    public function before()
    {
        $action_definition = $this->router->getMatchedActionDefinition();

        if (count($action_definition->getIncludableFieldList()) > 0) {
            $include_string = $this->request->query->get('include');
            if ($include_string !== null) {
                $include_list = LINQ::from(explode(',', $include_string))
                    ->select(function ($include_name) {
                        return trim(strtolower($include_name));
                    })
                    ->where(function ($include_name) use ($action_definition) {
                        if (in_array($include_name, $action_definition->getIncludableFieldList(), true)) {
                            return true;
                        }
                        return false;
                    })
                    ->toArray();
                $this->request->setIncludeList($include_list);
            }
        }
    }
}