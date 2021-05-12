<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function loginAPI($params = [])
    {
        $admin = $this->where('admin_name', $params['username'])->find();
        if ($admin) {
            if (password_verify($params['password'], $admin->password)) {
                $data = $admin->visible(['id', 'admin_name'])->toArray();
                $addition = [
                    'currentAuthority' => 'admin',
                    'type' => $params['type'] ?? null
                ];
                return $this->success('', $data, [], $addition);
            }
        }

        return $this->error('Incorrect username or password.');
    }
}
