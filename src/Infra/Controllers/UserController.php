<?php

namespace AppFinance\Infra\Controllers;

use AppFinance\Application\UseCases\CreateCommonUser\CreateCommonUser;
use AppFinance\Application\UseCases\CreateCommonUser\CreateCommonUserInputDto;
use AppFinance\Application\UseCases\CreateStoreUser\CreateStoreUser;
use AppFinance\Application\UseCases\CreateStoreUser\CreateStoreUserInputDto;
use AppFinance\Application\UseCases\Login\Login;
use AppFinance\Application\UseCases\TokenIsAuthorized\TokenIsAuthorized;
use AppFinance\Domain\Mappers\UserMap;
use AppFinance\Infra\Encrypter\JwtAdapter;
use AppFinance\Infra\Encrypter\PasswordHasherAdapter;
use AppFinance\Infra\Repositories\Sql\RepositorySqlFactory;
use AppFinance\Shared\Controller\ControllerBase;
use AppFinance\Shared\Controller\RequestController;
use AppFinance\Shared\Exceptions\LoginException;
use AppFinance\Shared\Validators\FieldsArrayValidator;

class UserController extends ControllerBase
{
    protected static function createCommonUser(RequestController $request): ?array
    {
        $body = $request->getBody();
        FieldsArrayValidator::create($body)
          ->checkField('name', 'Nome', 'text', ['max' => 100, 'min' => 2, 'blank' => false])
          ->checkField('cpf_cnpj', 'CPF / CNPJ', 'text', ['max' => 20, 'min' => 11, 'blank' => false])
          ->checkField('email', 'E-mail', 'text', ['max' => 100, 'blank' => false])
          ->checkField('password', 'Senha', 'text', ['max' => 20, 'min' => 8])
          ->checkField('repeat_password', 'Repetir senha', 'text', ['max' => 20, 'min' => 8]);
        $user_repository = RepositorySqlFactory::getUserRepository();
        $password_hasher = new PasswordHasherAdapter();
        $inputDto = new CreateCommonUserInputDto(
            $body['name'],
            $body['cpf_cnpj'],
            $body['email'],
            $body['password'],
            $body['repeat_password']
        );
        $use_case = new CreateCommonUser($user_repository, $password_hasher);
        $createCommonUserOutput = $use_case->execute($inputDto);
        return $createCommonUserOutput->mapToArray();
    }

    protected static function createStoreUser(RequestController $request): ?array
    {
        $body = $request->getBody();
        FieldsArrayValidator::create($body)
          ->checkField('name', 'Nome', 'text', ['max' => 100, 'min' => 2, 'blank' => false])
          ->checkField('cpf_cnpj', 'CPF / CNPJ', 'text', ['max' => 20, 'min' => 11, 'blank' => false])
          ->checkField('email', 'E-mail', 'text', ['max' => 100, 'blank' => false])
          ->checkField('password', 'Senha', 'text', ['max' => 20, 'min' => 8])
          ->checkField('repeat_password', 'Repetir senha', 'text', ['max' => 20, 'min' => 8]);
        $user_repository = RepositorySqlFactory::getUserRepository();
        $password_hasher = new PasswordHasherAdapter();
        $inputDto = new CreateStoreUserInputDto(
            $body['name'],
            $body['cpf_cnpj'],
            $body['email'],
            $body['password'],
            $body['repeat_password']
        );
        $use_case = new CreateStoreUser($user_repository, $password_hasher);
        $createCommonUserOutput = $use_case->execute($inputDto);
        return $createCommonUserOutput->mapToArray();
    }

    protected static function getAll(RequestController $request): ?array
    {
        $user_repository = RepositorySqlFactory::getUserRepository();
        $list_users = $user_repository->getAll();
        $list_users_array = [];
        foreach ($list_users as $user) {
            $list_users_array[] = UserMap::toArray($user);
        }
        return $list_users_array;
    }

    protected static function isAuthorized(RequestController $request): ?array
    {
        $headers = $request->getHeaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        if (! isset($headers['AUTHORIZATION'])) {
            throw new LoginException("Token Ausente.");
        }
        $token = $headers['AUTHORIZATION'];
        if (is_array($token)) {
            $token = $token[0];
        }
        $token = str_ireplace('Bearer ', '', $token);
        $jwt = new JwtAdapter();
        $use_case = new TokenIsAuthorized($jwt);
        $is_authorized = $use_case->execute($token);
        if (! $is_authorized) {
            throw new LoginException("Token is not autorized");
        }
        return ['message' => 'Token is Authorized'];
    }

    protected static function login(RequestController $request): ?array
    {
        $body = $request->getBody();
        FieldsArrayValidator::create($body)
          ->checkField('email')
          ->checkField('password');
        $user_repository = RepositorySqlFactory::getUserRepository();
        $password_hasher = new PasswordHasherAdapter();
        $jwt = new JwtAdapter();
        $use_case = new Login($user_repository, $password_hasher, $jwt);
        $loginResponse = $use_case->execute($body['email'], $body['password']);
        return $loginResponse;
    }
}