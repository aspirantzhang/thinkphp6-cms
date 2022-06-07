<?php

declare(strict_types=1);

namespace app\jwt\token;

use app\jwt\exception\TokenExpiredException;
use app\jwt\exception\TokenInvalidException;
use Carbon\CarbonImmutable;
use Firebase\JWT\ExpiredException as LIB_ExpiredException;
use Firebase\JWT\JWT as JWT_LIB;
use Firebase\JWT\Key;
use think\facade\Config;

abstract class TokenStrategy
{
    protected string $algorism = 'HS256';
    protected string $secretKey;
    protected array $claims;
    protected CarbonImmutable $now;

    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->now = CarbonImmutable::now();
        $this->secretKey = Config::get('jwt.key') ?? '';
        $this->initClaims();
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function getClaim(string $key)
    {
        return $this->claims[$key] ?? null;
    }

    public function addClaim(string $key, mixed $value)
    {
        $this->claims = [...$this->claims, $key => $value];

        return $this;
    }

    public function addClaims(array $values)
    {
        $this->claims = [...$this->claims, ...$values];

        return $this;
    }

    public function setClaim(string $key, mixed $value)
    {
        $this->claims[$key] = $value;

        return $this;
    }

    public function initClaims()
    {
        $this->claims = [
            'iss' => Config::get('jwt.iss'),
            'aud' => Config::get('jwt.aud'),
            'iat' => $this->now->getTimestamp(),
            'nbf' => $this->now->getTimestamp(),
            'exp' => $this->now->addSeconds((int) Config::get('jwt.exp'))->getTimestamp(),
        ];
    }

    public function checkToken(string $token)
    {
        try {
            $result = JWT_LIB::decode($token, new Key($this->secretKey, 'HS256'));

            return (array) $result;
        } catch (LIB_ExpiredException) {
            throw new TokenExpiredException('token expired');
        } catch (\Exception) {
            throw new TokenInvalidException('token invalid');
        }
    }

    abstract public function getToken();
}
