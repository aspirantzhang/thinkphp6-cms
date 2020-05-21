<?php
<<<<<<< HEAD

declare(strict_types=1);

namespace app\backend\controller;

=======
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
use app\backend\service\Admin as AdminService;

class Admin extends Common
{
    protected $adminService;
<<<<<<< HEAD

    public function initialize()
    {
        $this->adminService = new AdminService();
=======
    public function initialize()
    {
        $this->adminService = new AdminService;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        parent::initialize();
    }

    public function index()
    {
        $result = $this->adminService->listApi($this->request->only($this->adminService->allowIndex));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function create()
    {
        $result = $this->adminService->createApi();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function save()
    {
        $result = $this->adminService->saveApi($this->request->only($this->adminService->allowSave));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function read($id)
    {
        $result = $this->adminService->readApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function edit($id)
    {
        $result = $this->adminService->editApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function update($id)
    {
        $result = $this->adminService->updateApi($id, $this->request->only($this->adminService->allowUpdate));
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function delete($id)
    {
        $result = $this->adminService->deleteApi($id);
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return json($result);
    }

    public function groups($id)
    {
<<<<<<< HEAD
        $admin = $this->adminService->with(['groups' => function ($query) {
            $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1);
        }])->find($id);

        return json($admin->groups->hidden(['pivot']));
    }
=======
        $admin = $this->adminService->with(['groups'=>function($query) {
            $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1);
        }])->find($id);
        return json($admin->groups->hidden(['pivot']));
    }

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
