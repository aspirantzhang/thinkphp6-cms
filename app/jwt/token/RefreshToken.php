<?php

declare(strict_types=1);

namespace app\jwt\token;

use Firebase\JWT\JWT as JWT_LIB;
use think\facade\Config;

class RefreshToken extends TokenStrategy
{
    public function getToken()
    {
        $refreshExpire = $this->now->addSeconds((int) Config::get('jwt.renew'))->getTimestamp();
        $payload = $this->addClaim('exp', $refreshExpire)->getClaims();

        return JWT_LIB::encode($payload, $this->secretKey, $this->algorism);
    }
}
