<?php


namespace Fabs\Rest\Models\Response;

class QueryResponseModel extends ResponseModel
{
    public $total_count = 0;

    public function __construct($response, $total_count)
    {
        parent::__construct();

        $this->data = $response;
        $this->total_count = $total_count;
        $this->makeTransient('total_count');
    }
}