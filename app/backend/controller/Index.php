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

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function index()
    {
        return 'backend controller index';
    }
<<<<<<< HEAD

    public function login(AdminService $adminService)
    {
        $result = $adminService->loginApi($this->request->only($adminService->allowLogin));

        return json($result);
    }
=======
    public function login(AdminService $adminService)
    {
        $result = $adminService->loginApi($this->request->only($adminService->allowLogin));
        return json($result);
    }


>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
