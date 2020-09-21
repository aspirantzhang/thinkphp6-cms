<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\Admin as AdminModel;
use app\backend\service\AuthGroup;

class Admin extends AdminModel
{

    public function checkPassword($data)
    {
        $admin = $this->where('username', $data['username'])->where('status', 1)->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return -1;
        }
    }
}
