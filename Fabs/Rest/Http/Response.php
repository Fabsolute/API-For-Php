<?php


namespace Fabs\Rest\Http;

use Fabs\Rest\Constants\Headers;
use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Response extends SymfonyResponse
{
    /** @var mixed */
    private $returned_value = null;
    /** @var bool */
    private $is_sent = false;

    /**
     * @param mixed $returned_value
     * @return Response
     * @author ahmetturk <ahmetturk93@gmail.com>
     */
    public function setReturnedValue($returned_value)
    {
        $this->returned_value = $returned_value;
        if (is_string($returned_value)) {
            $this->setContent($returned_value);
        } else {
            if ($this->headers->get(Headers::CONTENT_TYPE) === 'application/json') {
                $json_content = json_encode($returned_value, JSON_PRESERVE_ZERO_FRACTION);
                if ($json_content === false) {
                    $json_content = '';
                }
                $this->setContent($json_content);
            }
        }
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

    /**
     * @return Response
     */
    public function send()
    {
        if ($this->is_sent === false) {
            $this->is_sent = true;
            parent::send();
        }

        return $this;
    }

    /**
     * @return Response
     */
    public function setContentTypeJson()
    {
        $this->headers->set(Headers::CONTENT_TYPE, 'application/json');
        return $this;
    }
}
