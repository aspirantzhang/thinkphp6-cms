<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\AuthGroup as AuthGroupService;

class AuthGroup extends Common
{
    protected $authGroupService;
    public function initialize()
    {
        $this->authGroupService = new AuthGroupService;
        parent::initialize();
    }

    public function index()
    {
        $result = $this->authGroupService->listApi($this->request->only($this->authGroupService->allowIndex));
        return json($result);
    }

    public function save()
    {
        $result = $this->authGroupService->saveApi($this->request->only($this->authGroupService->allowSave));
        return $result;
    }

    public function read($id)
    {
        $result = $this->authGroupService->readApi($id);
        return $result;
    }

    public function update($id)
    {
        $result = $this->authGroupService->updateApi($id, $this->request->only($this->authGroupService->allowUpdate));
        return $result;
    }

    public function delete($id)
    {
        $result = $this->authGroupService->deleteApi($id);
        return $result;
    }

    public function tree()
    {
        $result = $this->authGroupService->printTree($this->request->only($this->authGroupService->allowIndex));
        return json($result);
    }

}
