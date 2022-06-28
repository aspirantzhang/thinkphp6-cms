<?php

declare(strict_types=1);

namespace app\jwt\token;

use Firebase\JWT\JWT as JWT_LIB;

class AccessToken extends BaseToken
{
    protected string $tokenType = 'accessToken';

    public function getToken()
    {
        $this->checkUid();

        $claims = $this->getClaims();

        return JWT_LIB::encode($claims, $this->secretKey, $this->algorism);
    }
}
