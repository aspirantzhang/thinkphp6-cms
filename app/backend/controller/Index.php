<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\service\Admin as AdminService;

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }
    public function index()
    {
        return 'backend controller index';
    }
    public function login(AdminService $adminService)
    {
        $result = $adminService->loginApi($this->request->only($adminService->allowLogin));
        return $result;
    }


}
