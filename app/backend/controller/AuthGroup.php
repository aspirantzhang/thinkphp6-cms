<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\AuthGroup as AuthGroupService;

class AuthGroup extends Common
{
    protected $authGroup;

    public function initialize()
    {
        $this->authGroup = new AuthGroupService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->authGroup->treeListAPI($this->request->only($this->authGroup->allowHome), ['rules']);

        return $result;
    }

    public function add()
    {
        $result = $this->authGroup->addAPI();

        return $result;
    }

    public function save()
    {
        $result = $this->authGroup->saveAPI($this->request->only($this->authGroup->allowSave), ['rules']);

        return $result;
    }

    public function read($id)
    {
        return $this->authGroup->readAPI($id, ['rules']);
    }

    public function update($id)
    {
        $result = $this->authGroup->updateAPI($id, $this->request->only($this->authGroup->allowUpdate), ['rules']);

        return $result;
    }

    public function delete()
    {
        $result = $this->authGroup->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $result;
    }

    public function restore()
    {
        $result = $this->authGroup->restoreAPI($this->request->param('ids'));
        
        return $result;
    }
}
