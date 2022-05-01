<?php

namespace AppFinance\Infra\Encrypter;

use AppFinance\Protocols\Jwt as JWTProtocol;
use AppFinance\Shared\Exceptions\LoginException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

class JwtAdapter implements JWTProtocol
{
    private static $secret = "c3TjfAQcsrxWSVpsRm7xQrRUmcWSttAjA7AMRnrTEHVrrBGm";

    public function encrypt(array $payload_data): string
    {
        $dateTimeNow = new \DateTime("now", new \DateTimeZone("UTC"));
        $dateTimeExpire = \DateTime::createFromFormat("Y-m-d H:i:s", $dateTimeNow->format("Y-m-d H:i:s"), new \DateTimeZone("UTC"));
        $dateTimeExpire->add(\DateInterval::createFromDateString("1 day"));
        $payload = array(
            "iat" => $dateTimeNow->getTimestamp(),
            "exp" => $dateTimeExpire->getTimestamp()
        );
        $payload = array_merge($payload, $payload_data);
        $jwt = JWT::encode($payload, self::$secret, 'HS256');
        return $jwt;
    }

    public function decrypt(string $jwt)
    {
        try {
            return JWT::decode($jwt, self::$secret, array('HS256'));
        }
        catch (BeforeValidException $e) {
            throw new LoginException($e->getMessage());
        }
        catch (SignatureInvalidException $e) {
            throw new LoginException($e->getMessage());
        }
        catch (ExpiredException $e) {
            throw new LoginException($e->getMessage());
        }        
        catch (\UnexpectedValueException $e) {
            throw new LoginException($e->getMessage());
        }
        catch (\DomainException $e) {
            throw new LoginException('Invalid Token');
        }
    }
}