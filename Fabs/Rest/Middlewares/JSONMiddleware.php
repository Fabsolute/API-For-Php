<?php


namespace Fabs\Rest\Middlewares;


use Fabs\Rest\Constants\Headers;
use Fabs\Rest\Constants\HttpMethods;
use Fabs\Rest\Constants\ResponseStatus;
use Fabs\Rest\Exceptions\StatusCodeException\BadRequestException;
use Fabs\Rest\Exceptions\StatusCodeException\UnprocessableEntityException;
use Fabs\Rest\Exceptions\StatusCodeException\UnsupportedMediaTypeException;
use Fabs\Rest\MiddlewareBase;
use Fabs\Rest\Models\Response\ResponseModel;

class JSONMiddleware extends MiddlewareBase
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
}