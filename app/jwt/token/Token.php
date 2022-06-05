<?php

declare(strict_types=1);

namespace app\jwt\token;

class Token
{
    public function __construct(private TokenStrategy $tokenClass)
    {
    }

    public function getToken()
    {
        return $this->tokenClass->getToken();
    }

    public function checkToken(string $token)
    {
        return $this->tokenClass->checkToken($token);
    }
}
