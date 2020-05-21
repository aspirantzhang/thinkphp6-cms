<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\AuthRule as AuthRuleService;
=======
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\AuthRule as AuthRuleService;
use think\facade\Route;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

class AuthRule extends Common
{
    protected $authRuleService;
<<<<<<< HEAD

    public function initialize()
    {
        $this->authRuleService = new AuthRuleService();
=======
    public function initialize()
    {
        $this->authRuleService = new AuthRuleService;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        parent::initialize();
    }

    public function index()
    {
        $result = $this->authRuleService->listApi($this->request->only($this->authRuleService->allowIndex));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function create()
    {
        $result = $this->authRuleService->createApi();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function save()
    {
        $result = $this->authRuleService->saveApi($this->request->only($this->authRuleService->allowSave));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function read($id)
    {
        $result = $this->authRuleService->readApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function edit($id)
    {
        $result = $this->authRuleService->editApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function update($id)
    {
        $result = $this->authRuleService->updateApi($id, $this->request->only($this->authRuleService->allowUpdate));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function delete($id)
    {
        $result = $this->authRuleService->deleteApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function menus()
    {
        $result = $this->authRuleService->menuApi();
<<<<<<< HEAD

        return json($result);
    }
=======
        return json($result);
    }

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
