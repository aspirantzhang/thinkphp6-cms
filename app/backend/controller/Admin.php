<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\Admin as AdminService;

class Admin extends Common
{
    protected $adminService;
    public function initialize()
    {
        $this->adminService = new AdminService;
        parent::initialize();
    }

    public function index()
    {
        $result = $this->adminService->listApi($this->request->only($this->adminService->allowIndex));
        return json($result);
    }

    public function save()
    {
        $result = $this->adminService->saveApi($this->request->only($this->adminService->allowSave));
        return $result;
    }

    public function read($id)
    {
        $result = $this->adminService->readApi($id);
        return $result;
    }

    public function update($id)
    {
        $result = $this->adminService->updateApi($id, $this->request->only($this->adminService->allowUpdate));
        return $result;
    }

    public function delete($id)
    {
        $result = $this->adminService->deleteApi($id);
        return $result;
    }

}
