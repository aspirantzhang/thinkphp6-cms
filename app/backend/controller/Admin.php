<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\Admin as AdminService;

class Admin extends Common
{

    public function index(AdminService $adminService)
    {
        $result = $adminService->listApi($this->request->only($adminService->allowIndex));
        return json($result);
    }

    public function save(AdminService $adminService)
    {
        $result = $adminService->saveApi($this->request->only($adminService->allowSave));
        return $result;
    }

    public function read(AdminService $adminService, $id)
    {
        $result = $adminService->readApi($id);
        return $result;
    }

    public function update(AdminService $adminService, $id)
    {
        $result = $adminService->updateApi($id, $this->request->only($adminService->allowUpdate));
        return $result;
    }

    public function delete(AdminService $adminService, $id)
    {
        $result = $adminService->deleteApi($id);
        return $result;
    }

}
