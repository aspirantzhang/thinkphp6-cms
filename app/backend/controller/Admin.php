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
        $result = $this->admin->paginatedListAPI($this->request->only($this->admin->allowHome), ['groups']);

        return $result;
    }

    public function add()
    {
        $result = $this->admin->addAPI();

        return $result;
    }

    public function save()
    {
        $result = $this->admin->saveAPI($this->request->only($this->admin->allowSave), ['groups']);

        return $result;
    }

    public function read($id)
    {
        return $this->admin->readAPI($id, ['groups']);
    }

    public function update($id)
    {
        $result = $this->admin->updateAPI($id, $this->request->only($this->admin->allowUpdate), ['groups']);

        return $result;
    }

    public function delete()
    {
        $result = $this->admin->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $result;
    }

    public function restore()
    {
        $result = $this->admin->restoreAPI($this->request->param('ids'));
        
        return $result;
    }
}
