<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\AuthRule as AuthRuleService;

class AuthRule extends Common
{
    protected $authRule;

    public function initialize()
    {
        $this->authRule = new AuthRuleService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->authRule->treeListAPI($this->request->only($this->authRule->allowHome));

        return $result;
    }

    public function add()
    {
        $result = $this->authRule->addAPI();

        return $result;
    }

    public function save()
    {
        $result = $this->authRule->saveAPI($this->request->only($this->authRule->allowSave));

        return $result;
    }

    public function read($id)
    {
        return $this->authRule->readAPI($id);
    }

    public function update($id)
    {
        $result = $this->authRule->updateAPI($id, $this->request->only($this->authRule->allowUpdate));

        return $result;
    }

    public function delete()
    {
        $result = $this->authRule->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $result;
    }
}
