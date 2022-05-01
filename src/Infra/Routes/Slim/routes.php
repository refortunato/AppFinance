<?php

use AppFinance\Infra\Controllers\UserController;
use AppFinance\Infra\Routes\Adapters\SlimControllerAdapter;
use AppFinance\Infra\Routes\Slim\JsonBodyParserMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();

// Convert To Json
$app->add(new JsonBodyParserMiddleware());


//Users
$app->post('/common-user', function (Request $request, Response $response, $args) {
    return SlimControllerAdapter::create($request, $response, $args)->execute(UserController::class, 'createCommonUser');
});
$app->post('/store-user', function (Request $request, Response $response, $args) {
    return SlimControllerAdapter::create($request, $response, $args)->execute(UserController::class, 'createStoreUser');
});
$app->post('/login', function (Request $request, Response $response, $args) {
    return SlimControllerAdapter::create($request, $response, $args)->execute(UserController::class, 'login');
});




###############################
// Error Handler
###############################
// Define Custom Error Handler
$customErrorHandler = function (
    ServerRequestInterface $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {
    if (! empty($logger)) {
        $logger->error($exception->getMessage());
    }

    $payload = ['error' => $exception->getMessage()];

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response->withStatus($exception->getCode());
};
/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger  
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
//$errorHandler->forceContentType('application/json');
###############################
// End Error Handler
###############################

// Run app
$app->run();