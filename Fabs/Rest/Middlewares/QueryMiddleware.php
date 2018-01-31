<?php


namespace Fabs\Rest\Middlewares;


use Fabs\Rest\Constants\Headers;
use Fabs\Rest\MiddlewareBase;
use Fabs\Rest\Models\Response\QueryResponseModel;
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

        $search_queries = new SearchQueryModel();
        $sort_by = $this->request->getQuery('sort_by');
        $sort_by_descending = $this->request->getQuery('sort_by_descending');
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
                    $search_queries->addQueryElement($query_element->setValue($query_value));
                }
            }

            if ($sort_by !== null || $sort_by_descending !== null) {
                if ($query_element instanceof SortQueryElement) {
                    $sort_name = $sort_by ?? $sort_by_descending;
                    if ($query_element->getQueryName() === $sort_name) {
                        if ($sort_by === null) {
                            $query_element->setDescending(true);
                        } else {
                            $query_element->setDescending(false);
                        }
                        $search_queries->setSortQueryElement($query_element);
                    }
                }
            }
        }

        if ($search_queries->getSortQueryElement() === null) {
            $search_queries->setSortQueryElement($action_definition->getDefaultSortQueryElement());
        }

        $search_queries->setPage($this->request->getIntQuery('page'));
        $search_queries->setPage($this->request->getIntQuery('per_page'));

        $this->request->setSearchQueryModel($search_queries);
    }

    public function after()
    {
        $response = $this->response->getReturnedValue();
        if ($response instanceof QueryResponseModel) {
            $this->response->setHeader(Headers::X_TOTAL_COUNT, $response->total_count);
        }
    }
}