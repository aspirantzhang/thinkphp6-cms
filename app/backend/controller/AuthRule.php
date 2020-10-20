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
        $result = $this->authRule->treeListAPI($this->request->only($this->authRule->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->authRule->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $result = $this->authRule->saveAPI($this->request->only($this->authRule->getAllowSave()));

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->authRule->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->authRule->updateAPI($id, $this->request->only($this->authRule->getAllowUpdate()));

        return $this->json(...$result);
    }

    public function delete()
    {
        $result = $this->authRule->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->authRule->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
}
