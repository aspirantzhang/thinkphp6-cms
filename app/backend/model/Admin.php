<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;

class Admin extends Common
{
    protected $readonly = ['name'];
    protected $globalScope = ['status'];
    public function scopeStatus($query)
    {
        $query->where('status', 1);
    }

    public function loginCheck($data)
    {
        $admin = $this->where('username', $data['username'])->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return false;
        }
    }
    public function saveNew($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        $admin = Admin::create($data);
        if ($admin) {
            return $admin->id;
        } else {
            return 0;
        }
    }
    public function deleteID($id)
    {
        $admin = Admin::find($id);
        return $admin->delete();
    }
}
