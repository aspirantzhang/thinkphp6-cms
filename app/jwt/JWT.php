<?php

declare(strict_types=1);

namespace app\jwt;

use DateTimeImmutable;
use Firebase\JWT\JWT as JWT_LIB;
use think\facade\Config;

class JWT
{
    public function getToken()
    {
        $key = 'T3z6kcwNEJt%wXBBec_wSrpCCNhX3FFf';

        $now = new DateTimeImmutable();
        $payload = [
            'iss' => Config::get('jwt.iss'),
            'aud' => Config::get('jwt.aud'),
            'iat' => $now->getTimestamp(),
            'nbf' => $now->getTimestamp(),
            'exp' => $now->modify('+ ' . (int) Config::get('jwt.exp') . ' seconds')->getTimestamp(),
        ];

        return JWT_LIB::encode($payload, $key, 'HS256');
    }
}
