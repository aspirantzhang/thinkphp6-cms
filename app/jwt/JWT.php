<?php

declare(strict_types=1);

namespace app\jwt;

use app\jwt\token\AccessToken;
use app\jwt\token\RefreshToken;
use app\jwt\token\Token;

class JWT
{
    public function getToken()
    {
        $accessToken = (new Token(new AccessToken()))->getToken();
        $refreshToken = (new Token(new RefreshToken()))->getToken();

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];
    }
}
