<?php


namespace Fabs\Rest\ExceptionHandlers;

use Fabs\Rest\Constants\ResponseStatus;
use Fabs\Rest\ExceptionHandlerBase;
use Fabs\Rest\Models\Response\ErrorResponseModel;

class NotFoundExceptionHandler extends ExceptionHandlerBase
{
    /**
     * @param \Fabs\Rest\Exceptions\StatusCodeException\NotFoundException $exception
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function handle($exception)
    {
        $error_response_model = new ErrorResponseModel();
        $error_response_model->status = ResponseStatus::FAILURE;
        $error_response_model->error_message = $exception->getMessage();
        $error_response_model->error_details = $exception->getErrorDetails();

        $this->response
            ->setReturnedValue(
                $error_response_model
            )
            ->setStatusCode($exception->getCode())
            ->send();
    }
}