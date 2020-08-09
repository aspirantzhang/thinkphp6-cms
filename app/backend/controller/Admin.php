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

    public function index()
    {
        $result = $this->admin->listApi($this->request->only($this->admin->allowIndex));

        return $result;
    }

    public function add()
    {
        $result = $this->admin->addApi();

        return $result;
    }

    public function save()
    {
        $result = $this->admin->saveApi($this->request->only($this->admin->allowSave));

        return $result;
    }

    public function read($id)
    {
        return $this->admin->readApi($id);
    }

    public function edit($id)
    {
        $result = $this->admin->editApi($id);

        return json($result);
    }

    public function update($id)
    {
        $result = $this->admin->updateApi($id, $this->request->only($this->admin->allowUpdate));

        return $result;
    }

    public function delete($id)
    {
        $result = $this->admin->deleteApi($id);

        return $result;
    }

    public function batchDelete()
    {
        $result = $this->admin->batchDeleteApi($this->request->param('idArray'));
        
        return $result;
    }

    public function groups($id)
    {
        $admin = $this->admin->with(['groups' => function ($query) {
            $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1);
        }])->find($id);

        return json($admin->groups->hidden(['pivot']));
    }
}
