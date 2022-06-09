<?php

declare(strict_types=1);

namespace app\jwt;

use app\jwt\token\AccessToken;
use app\jwt\token\RefreshToken;

class JWT
{
    public function getToken($payload = [])
    {
        $accessToken = (new AccessToken())->addClaims($payload)->getToken();
        $refreshToken = (new RefreshToken())->getToken();

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }
}
