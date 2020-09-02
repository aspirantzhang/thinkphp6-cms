<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Admin as AdminService;

class Admin extends Common
{
    protected $admin;

    public function initialize()
    {
        $this->admin = new AdminService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->admin->listAPI($this->request->only($this->admin->allowHome));

        return $result;
    }

    public function add()
    {
        $result = $this->admin->addAPI();

        return $result;
    }

    public function save()
    {
        $result = $this->admin->saveAPI($this->request->only($this->admin->allowSave));

        return $result;
    }

    public function read($id)
    {
        return $this->admin->readAPI($id);
    }

    public function update($id)
    {
        $result = $this->admin->updateAPI($id, $this->request->only($this->admin->allowUpdate));

        return $result;
    }

    public function delete($id)
    {
        $result = $this->admin->deleteAPI($id);

        return $result;
    }

    public function batchDelete()
    {
        $result = $this->admin->batchDeleteAPI($this->request->param('idArray'));
        
        return $result;
    }

    public function test()
    {
        return json($this->admin->testAPI([]));
    }
}
