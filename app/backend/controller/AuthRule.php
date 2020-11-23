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
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function read($id)
    {
        $result = $this->authRule->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function delete()
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }

    public function restore()
    {
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted);
    }
}
