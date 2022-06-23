<?php

declare(strict_types=1);

namespace app\core\domain\Login;

class JwtVisitor implements LoginVisitor
{
    private Login $login;
    private array $payload;

    public function visitLogin(Login $login)
    {
        $this->login = $login;
    }

    /**
     * set user properties included in token payload.
     *
     * @param array $userProperties [ ...[KeyInResult => PropertyNameOfUser] ]
     */
    public function withUserProps(array $userProperties)
    {
        foreach ($userProperties as $key => $property) {
            $this->payload[$key] = $this->login->getUser()->{$property} ?? null;
        }

        return $this;
    }

    private function getPayloadAndToken()
    {
        $token = app('jwt')->setStateful(true)->getToken($this->payload);

        return [$this->payload, $token];
    }

    public function getResult()
    {
        [$payload, $token] = $this->getPayloadAndToken();

        return [...$payload, ...$token];
    }
}
