<?php

namespace Modules\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Infrastructure\Base\BaseService;
use Throwable;

class AuthService extends BaseService
{
    /**
     * Decodifica y valida la firma del token JWT.
     */
    public function checkToken(string $token):?object
    {
       try{
        $secrect = $_ENV['JWT_SECRET']?? 'default_secret';
        return JWT::decode($token, new Key($secrect,'HS256'));
       }catch(Throwable $e){
         return null;
       }
    }
}