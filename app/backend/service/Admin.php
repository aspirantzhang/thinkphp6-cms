<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;
use app\backend\service\AuthGroup;

class Admin extends AdminLogic
{
    public function loginAPI($params = [])
    {
        $admin = $this->where('username', $params['username'])->find();
        if ($admin) {
            if (password_verify($params['password'], $admin->password)) {
                $data = [
                    'currentAuthority' => 'admin',
                    'type' => $params['type'] ?? null
                ];
                return $this->success('', [], [], $data);
            }
        }

        return $this->error('Incorrect username or password.');
    }
}
