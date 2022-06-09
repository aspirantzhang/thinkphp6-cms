<?php

declare(strict_types=1);

namespace app\jwt\token;

use Firebase\JWT\JWT as JWT_LIB;

class AccessToken extends BaseToken
{
    public function getToken()
    {
        $payload = $this->getClaims();

        return JWT_LIB::encode($payload, $this->secretKey, $this->algorism);
    }
}
