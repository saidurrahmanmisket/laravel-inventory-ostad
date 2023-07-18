<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{

   public static function createToken($userEmail) {
        $key = env('JWT_key');
            $payload = [
                'iss' => 'laravel-token',
                'iat' => time(),
                'exp' => time()+60*60,
                'userEmail' => $userEmail
            ];

            return JWT::encode($payload,$key,'HS256');

    }
   public static function createTokenForPass($userEmail) {
        $key = env('JWT_key');
            $payload = [
                'iss' => 'laravel-token',
                'iat' => time(),
                'exp' => time()+60*60,
                'userEmail' => $userEmail
            ];

            return JWT::encode($payload,$key,'HS256');

    }

    public static function verifyToken($token):string
    {

        try{
            $key = env('JWT_key');
            $decode =  JWT::decode($token, new Key($key,'HS256'));
           return $decode->userEmail;
           
        }
        catch(Exception $e){
            return response()->json([

                'status' => 'fail',
                'message' => 'Unauthorize'

            ], status: 401);
        }


    }
}