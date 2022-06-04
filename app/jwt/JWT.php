<?php

declare(strict_types=1);

namespace app\jwt;

use DateTimeImmutable;
use Firebase\JWT\JWT as JWT_LIB;
use think\facade\Config;

class JWT
{
    private string $algorism = 'HS256';
    private string $secretKey;
    private array $claims;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->secretKey = Config::get('jwt.key') ?? '';
        $this->initClaims();
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function addClaim(string $key, mixed $value)
    {
        $this->claims = [...$this->claims, $key => $value];

        return $this;
    }

    public function setClaim(string $key, mixed $value)
    {
        $this->claims[$key] = $value;

        return $this;
    }

    public function initClaims()
    {
        $now = new DateTimeImmutable();

        $this->claims = [
            'iss' => Config::get('jwt.iss'),
            'aud' => Config::get('jwt.aud'),
            'iat' => $now->getTimestamp(),
            'nbf' => $now->getTimestamp(),
            'exp' => $now->modify('+ ' . (int) Config::get('jwt.exp') . ' seconds')->getTimestamp(),
        ];
    }

    public function getAccessToken()
    {
        $payload = $this->getClaims();

        return JWT_LIB::encode($payload, $this->secretKey, $this->algorism);
    }
}
