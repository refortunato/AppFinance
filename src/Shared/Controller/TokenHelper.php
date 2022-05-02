<?php

namespace AppFinance\Shared\Controller;

use AppFinance\Application\UseCases\GetUserByToken\GetUserByToken;
use AppFinance\Domain\Entities\User;
use AppFinance\Infra\Encrypter\JwtAdapter;
use AppFinance\Infra\Repositories\Sql\RepositorySqlFactory;

trait TokenHelper
{
    public static function getUserFromToken(RequestController $request): ?User
    {
        $user_repository = RepositorySqlFactory::getUserRepository();
        $jwt = new JwtAdapter();
        $use_case_get_user_by_token = new GetUserByToken($user_repository, $jwt);
        $token = self::getTokenFromRequestHeader($request);
        $user = $use_case_get_user_by_token->execute($token);
        return $user;
    }

    public static function getTokenFromRequestHeader(RequestController $request): ?string
    {
        $headers = $request->getHeaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        if (! isset($headers['AUTHORIZATION'])) {
            return null;
        }
        $token = $headers['AUTHORIZATION'];
        if (is_array($token)) {
            $token = $token[0];
        }
        $token = str_ireplace('Bearer ', '', $token);
        return $token;
    }
}