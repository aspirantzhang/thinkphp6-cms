<?php

declare(strict_types=1);

namespace app\jwt\token;

use Firebase\JWT\JWT as JWT_LIB;
use think\facade\Config;

class RefreshToken extends BaseToken
{
    protected string $tokenType = 'refreshToken';

    public function getToken()
    {
        $this->checkUid();

        $refreshExpire = $this->now->addSeconds((int) Config::get('jwt.renew'))->getTimestamp();
        $jti = $this->getUniqueId();
        $payload = $this->addClaim('exp', $refreshExpire)
            ->addClaim('jti', $jti)
            ->getClaims();

        return JWT_LIB::encode($payload, $this->secretKey, $this->algorism);
    }

    private function getUniqueId()
    {
        $id = uniqid();
        $addLength = 12;
        if (function_exists('random_bytes')) {
            $id .= substr(bin2hex(random_bytes((int) ceil(($addLength) / 2))), 0, $addLength);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $id .= substr(bin2hex(openssl_random_pseudo_bytes((int) ceil($addLength / 2))), 0, $addLength);
        } else {
            $id .= mt_rand(1 * pow(10, ($addLength)), 9 * pow(10, ($addLength)));
        }

        return $id;
    }
}
