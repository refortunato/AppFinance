<?php

namespace AppFinance\Shared\Controller;

class RequestController
{
    private $params;
    private $query_params;
    private $body;
    private $headers;

    public function __construct(
        $params,
        $query_params,
        $body,
        $headers
    )
    {
        $this->params = $params;
        $this->query_params = $query_params;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getQueryParams()
    {
        return $this->query_params;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}