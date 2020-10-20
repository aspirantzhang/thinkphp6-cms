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
        $result = $this->authGroup->treeListAPI($this->request->only($this->authGroup->getAllowHome()), ['rules']);

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->authGroup->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->authGroup->saveAPI($this->request->only($this->authGroup->getAllowSave()), ['rules']);

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->authGroup->readAPI($id, ['rules']);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->authGroup->updateAPI($id, $this->request->only($this->authGroup->getAllowUpdate()), ['rules']);

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->authGroup->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->authGroup->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
}
