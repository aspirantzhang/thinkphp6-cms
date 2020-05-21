<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\controller;

=======
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
use app\backend\service\AuthGroup as AuthGroupService;

class AuthGroup extends Common
{
    protected $authGroupService;
<<<<<<< HEAD

    public function initialize()
    {
        $this->authGroupService = new AuthGroupService();
=======
    public function initialize()
    {
        $this->authGroupService = new AuthGroupService;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        parent::initialize();
    }

    public function index()
    {
        $result = $this->authGroupService->listApi($this->request->only($this->authGroupService->allowIndex));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function create()
    {
        $result = $this->authGroupService->createApi();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function save()
    {
        $result = $this->authGroupService->saveApi($this->request->only($this->authGroupService->allowSave));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function read($id)
    {
        $result = $this->authGroupService->readApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function edit($id)
    {
        $result = $this->authGroupService->editApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function update($id)
    {
        $result = $this->authGroupService->updateApi($id, $this->request->only($this->authGroupService->allowUpdate));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function delete($id)
    {
        $result = $this->authGroupService->deleteApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function tree()
    {
        $result = $this->authGroupService->printTree($this->request->only($this->authGroupService->allowIndex));
<<<<<<< HEAD

        return json($result);
    }
=======
        return json($result);
    }

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
