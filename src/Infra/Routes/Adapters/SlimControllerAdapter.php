<?php

namespace AppFinance\Infra\Routes\Adapters;

use AppFinance\Shared\Controller\RequestController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class SlimControllerAdapter
{
    private ServerRequestInterface $request;
    private ResponseInterface $response;
    private $args;
    private RequestController $requestController;

    private function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        $this->requestController = new RequestController(
            $this->args,
            $this->request->getQueryParams(),
            $this->request->getParsedBody(),
            $this->request->getHeaders()
        );
    }

    public static function create(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    )
    {
        return new static(
            $request,
            $response,
            $args
        );
    }

    public function execute(string $class, string $method)
    {   
        return $this->executeControllerMethod($class, $method, $this->response);
    }

    public function executeWithAuth(string $class, string $method)
    {
        $response_auth = new \Slim\Psr7\Response();
        $response_auth = $this->executeControllerMethod(UserController::class, 'isAuthorized', $response_auth);
        //var_dump($response_auth->getStatusCode());
        if ($response_auth->getStatusCode() != 200) {
            return $response_auth;
        }
        return $this->execute($class, $method);
    }

    private function executeControllerMethod(string $class, string $method, ?ResponseInterface $response = null)
    {
        $result = call_user_func_array($class.'::execute',[$method, $this->requestController]);
        $data = $result->getData();
        if (is_array($data)) {
            //$data = json_encode($data,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            $data = json_encode($data);
        }
        $response->getBody()->write($data);
        return $response
          ->withHeader('Content-Type', 'application/json')
          ->withStatus($result->getHttpStatus());
    }
}