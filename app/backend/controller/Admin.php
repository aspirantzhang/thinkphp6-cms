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
        $result = $this->facade->getBasicPaginatedList();

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

    public function add()
    {
        return 'add action';
    }

    public function store()
    {
        $result = $this->facade->store();

        return $this->jsonView($result);
    }

    public function view($id)
    {
        return 'view action';
    }

    public function update($id)
    {
        $result = $this->facade->update((int) $id);

        return $this->jsonView($result);
    }
}
