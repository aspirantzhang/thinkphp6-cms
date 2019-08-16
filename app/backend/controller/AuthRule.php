<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\AuthRule as AuthRuleService;

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

    public function save()
    {
        $result = $this->authRuleService->saveApi($this->request->only($this->authRuleService->allowSave));
        return $result;
    }

    public function read($id)
    {
        $result = $this->authRuleService->readApi($id);
        return $result;
    }

    public function update($id)
    {
        $result = $this->authRuleService->updateApi($id, $this->request->only($this->authRuleService->allowUpdate));
        return $result;
    }

    public function delete($id)
    {
        $result = $this->authRuleService->deleteApi($id);
        return $result;
    }

}
