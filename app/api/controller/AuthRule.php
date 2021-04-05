<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\AuthRule as AuthRuleService;

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
/*         // API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
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
/*         // API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->authRule->updateAPI($id, $this->request->only($this->authRule->getAllowUpdate()), ['rules']);

        return $this->json(...$result);
    }

    public function delete()
    {
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->authRule->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function restore()
    {
/*         // for API
        $notPermitted = [
            'success' => false,
            'message' => 'Operation not permitted.'
        ];
        return $this->json($notPermitted); */
        $result = $this->authRule->restoreAPI($this->request->param('ids'));
        
        return $this->json(...$result);
    }
}
