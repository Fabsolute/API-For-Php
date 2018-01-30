<?php


namespace Fabs\Rest\Middlewares;


use Fabs\LINQ\LINQ;
use Fabs\Rest\Constants\ResponseStatus;
use Fabs\Rest\Exceptions\StatusCodeException\BadRequestException;
use Fabs\Rest\Exceptions\StatusCodeException\UnprocessableEntityException;
use Fabs\Rest\MiddlewareBase;
use Fabs\Rest\Constants\Headers;
use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\Exceptions\StatusCodeException\UnsupportedMediaTypeException;
use Fabs\Rest\Models\Response\ResponseModel;
use Fabs\Rest\Models\Search\SearchQueryModel;
use Fabs\Rest\Models\Search\SortQueryElement;
use Fabs\Serialize\Validation\ValidationException;

class RESTMiddleware extends MiddlewareBase
{
    public function before()
    {
        $is_data_required = $this->request->isMethod(HttpMethods::POST) ||
            $this->request->isMethod(HttpMethods::PUT) ||
            $this->request->isMethod(HttpMethods::PATCH);

        if ($is_data_required) {
            $content_type = $this->request->getHeader(Headers::CONTENT_TYPE);
            if ($content_type !== 'application/json') {
                throw new UnsupportedMediaTypeException([
                    Headers::CONTENT_TYPE => $content_type,
                    'expected' => 'application/json'
                ]);
            }

            $data = $this->request->getArrayBody();
            if (count($data) === 0) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw  new BadRequestException();
                } else {
                    throw new UnprocessableEntityException();
                }
            }
        }

        $this->parseQuery();
        $this->parseInclude();
    }

    public function after()
    {
        if ($this->response->isSent() === false) {
            $returned_value = $this->response->getReturnedValue();
            if ($returned_value instanceof ResponseModel) {
                $response_model = $returned_value;
            } else {
                $response_model = new ResponseModel();
                $response_model->status = ResponseStatus::SUCCESS;
                $response_model->data = $returned_value;
            }

            $this->response->setReturnedValue($response_model);
        }
    }

    public function finalize()
    {
        if ($this->response->isSent() === false) {
            $this->response->send();
        }
    }

    private function parseQuery()
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

    private function parseInclude()
    {
        $action_definition = $this->router->getMatchedActionDefinition();

        if (count($action_definition->getIncludableFieldList()) > 0) {
            $include_string = $this->request->getQuery('include');
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