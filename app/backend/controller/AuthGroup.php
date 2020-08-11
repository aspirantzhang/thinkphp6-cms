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

    public function index()
    {
        $result = $this->authGroup->treeListApi($this->request->only($this->authGroup->allowIndex));

        return $result;
    }

    public function tree()
    {
        $result = $this->authGroup->treeListApi($this->request->only($this->authGroup->allowIndex));

        return $result;
    }

    public function add()
    {
        $result = $this->authGroup->addApi();

        return $result;
    }

    public function save()
    {
        $result = $this->authGroup->saveApi($this->request->only($this->authGroup->allowSave));

        return $result;
    }

    public function read($id)
    {
        return $this->authGroup->readApi($id);
    }

    public function edit($id)
    {
        $result = $this->authGroup->editApi($id);

        return json($result);
    }

    public function update($id)
    {
        $result = $this->authGroup->updateApi($id, $this->request->only($this->authGroup->allowUpdate));

        return $result;
    }

    public function delete($id)
    {
        $result = $this->authGroup->deleteApi($id);

        return $result;
    }

    public function test()
    {
        $result = $this->authGroup->getParentAPI();

        return json($result);
    }
}
