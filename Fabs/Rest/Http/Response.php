<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Constants\Headers;
use Fabs\Rest\Injectable;

class Response extends Injectable
{
    private $returned_value = null;
    /** @var bool */
    private $is_sent = false;
    /** @var string[] */
    private $headers = [];

    public function setReturnedValue($returned_value)
    {
        $this->returned_value = $returned_value;
        return $this;
    }

    public function getReturnedValue()
    {
        return $this->returned_value;
    }

    /**
     * @return bool
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function isSent()
    {
        return $this->is_sent;
    }

    public function send()
    {
        $this->is_sent = true;
        // todo headers
        if (is_array($this->getReturnedValue()) || $this->getReturnedValue() instanceof \JsonSerializable) {
            $this->setHeader(Headers::CONTENT_TYPE, 'application/json');

            $this->sendHeaders();
            $this->sendContent();
        }
    }

    /**
     * @param string $header_name
     * @param string $value
     * @return Response
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setHeader($header_name, $value)
    {
        $this->headers[$header_name] = $value;
        return $this;
    }

    private function sendContent()
    {
        echo(json_encode($this->getReturnedValue(), JSON_PRESERVE_ZERO_FRACTION));
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $header_name => $value) {
            header($header_name . ':' . $value);
        }
    }
}