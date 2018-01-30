<?php


namespace Fabs\Rest\Middlewares;


use Fabs\Rest\MiddlewareBase;
use Fabs\Rest\Models\Search\SearchQueryModel;
use Fabs\Rest\Models\Search\SortQueryElement;

class QueryMiddleware extends MiddlewareBase
{

    public function before()
    {
        $action_definition = $this->router->getMatchedActionDefinition();

        if ($action_definition->getDefaultSortQueryElement() !== null) {
            $action_definition->addQueryElement($action_definition->getDefaultSortQueryElement());
        }

        $query_element_list = [];
        $sort_by = $this->request->getQuery('sort_by');
        $sort_by_descending = $this->request->getQuery('sort_by_descending');
        $sort_query_element = null;
        foreach ($action_definition->getQueryElementList() as $query_element) {
            $query_value = $this->request->getQuery($query_element->getQueryName());
            if ($query_value !== null) {
                if (is_callable($query_element->getFilter())) {
                    $query_value = call_user_func($query_element->getFilter(), $query_value);
                }

                $validated = true;
                $validation_list = $query_element->getValidationList();
                foreach ($validation_list as $validation) {
                    $validated = $validation->isValid($query_value);
                    if ($validated === false) {
                        $continue_application = $query_element->fireValidationFailed($validation);
                        if ($continue_application === false) {
                            return;
                        }
                        break;
                    }
                }

                if ($validated) {
                    $query_element_list[] = $query_element->setValue($query_value);
                }
            }

            if ($sort_by !== null || $sort_by_descending !== null) {
                if ($query_element instanceof SortQueryElement) {
                    $sort_name = $sort_by ?? $sort_by_descending;
                    if ($query_element->getQueryName() === $sort_name) {
                        $sort_query_element = $query_element;
                        if ($sort_by === null) {
                            $sort_query_element->setDescending(true);
                        } else {
                            $sort_query_element->setDescending(false);
                        }
                    }
                }
            }
        }

        if (count($query_element_list) > 0 || (($sort_by !== null || $sort_by_descending !== null) && $sort_query_element !== null)) {
            if ($sort_query_element === null) {
                $sort_query_element = $action_definition->getDefaultSortQueryElement();
            }

            $search_queries = new SearchQueryModel($query_element_list, $sort_query_element);
            $this->request->setSearchQueryModel($search_queries);
        }

    }
}