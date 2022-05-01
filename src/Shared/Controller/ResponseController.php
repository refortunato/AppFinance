<?php

namespace AppFinance\Shared\Controller;

class ResponseController
{
    private int $http_status;
    private $data;

    private function __construct(
        $data,
        int $http_status
    )
    {
        $this->data = $data;
        $this->http_status = $http_status;
    }

    public static function create($data, int $http_status)
    {
        return new static($data, $http_status);
    }

    public function getHttpStatus(): int
    {
        return $this->http_status;
    }

    public function getData()
    {
        return $this->data;
    }


}