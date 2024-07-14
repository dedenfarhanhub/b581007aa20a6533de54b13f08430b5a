<?php

namespace App\JWT;

use Firebase\JWT\JWT;

class JWTWrapper
{
    public function encode(array $token, string $algorithm): string
    {
        return JWT::encode($token, JWT_SECRET_KEY, $algorithm);
    }
}
