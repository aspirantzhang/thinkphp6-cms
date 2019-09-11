<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\AuthRule as AuthRuleService;
use think\facade\Route;

class AuthRule extends Common
{
    protected $authRuleService;
    public function initialize()
    {
        $this->authRuleService = new AuthRuleService;
        parent::initialize();
    }

    public function index()
    {
        $result = $this->authRuleService->listApi($this->request->only($this->authRuleService->allowIndex));
        return json($result);
    }

    public function create()
    {
        $result = $this->authRuleService->createApi();
        return json($result);
    }

    public function save()
    {
        $result = $this->authRuleService->saveApi($this->request->only($this->authRuleService->allowSave));
        return json($result);
    }

    public function read($id)
    {
        $result = $this->authRuleService->readApi($id);
        return json($result);
    }

    public function edit($id)
    {
        $result = $this->authRuleService->editApi($id);
        return json($result);
    }

    public function update($id)
    {
        $result = $this->authRuleService->updateApi($id, $this->request->only($this->authRuleService->allowUpdate));
        return json($result);
    }

    public function delete($id)
    {
        $result = $this->authRuleService->deleteApi($id);
        return json($result);
    }

    public function menus()
    {
        $result = $this->authRuleService->menuApi();
        return json($result);
    }

}
