<?php

namespace app\backend\model;

use app\backend\model\Common;

class Admin extends Common
{
    public function loginCheck($data)
    {
        $admin = $this->where('username', $data['username'])->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return false;
        }
    }
}
