<?php

declare(strict_types=1);

namespace app\backend\controller;

class Admin extends Common
{
    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $result = $this->facade->getPaginatedList();

        return $this->jsonView($result);
    }

    public function login()
    {
        $result = $this->facade->login();

        return $this->jsonView($result);
    }

    public function refreshToken()
    {
        $result = $this->facade->refreshToken();

        return $this->jsonView($result);
    }
}
