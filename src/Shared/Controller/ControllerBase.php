<?php

namespace AppFinance\Shared\Controller;

use AppFinance\Shared\Exceptions\LoginException;
use AppFinance\Shared\Exceptions\NotFoundException;
use AppFinance\Shared\Exceptions\ValidationException;
use AppFinance\Shared\Helpers\HttpStatus;

abstract class ControllerBase
{
    public static function execute(string $method_name, RequestController $request): ?ResponseController
    {
        if (method_exists(static::class, $method_name)) {
            try {
                $result = static::$method_name($request);
                return ResponseController::create($result, HttpStatus::OK);
            } catch (NotFoundException $e) {
                return ResponseController::create(['message' => $e->getMessage()], HttpStatus::NOT_FOUND);
            } catch (ValidationException $e) {
                return ResponseController::create(['message' => $e->getMessage()], HttpStatus::BAD_REQUEST);
            } catch (\DomainException $e) {
                return ResponseController::create(['message' => $e->getMessage()], HttpStatus::BAD_REQUEST);
            } catch (\InvalidArgumentException $e) {
                return ResponseController::create(['message' => $e->getMessage()], HttpStatus::BAD_REQUEST);
            } catch (LoginException $e) {
                return ResponseController::create(['message' => $e->getMessage()], HttpStatus::UNAUTHORIZED);
            } catch (\Exception $e) {
                return  ResponseController::create(['message' => 'Internal Server Error 1', 'error' => $e->getMessage()], HttpStatus::INTERNAL_SERVER_ERROR);
            } catch (\Error $e){
                return  ResponseController::create(['message' => 'Internal Server Error 2', 'error' => $e->getMessage()], HttpStatus::INTERNAL_SERVER_ERROR);
            }
            catch (\Throwable $t){
                return  ResponseController::create(['message' => 'Internal Server Error 3', 'error' => $t->getMessage()], HttpStatus::INTERNAL_SERVER_ERROR);
            }
        }
        return ResponseController::create(['message' => 'Not Implemented'], HttpStatus::NOT_IMPLEMENTED);
    }
}