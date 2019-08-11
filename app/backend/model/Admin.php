<?php
declare (strict_types = 1);

namespace app\backend\model;

use app\backend\model\Common;
use think\model\concern\SoftDelete;

class Admin extends Common
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $readonly = ['name'];
    protected $globalScope = ['status'];

    public function scopeStatus($query)
    {
        $query->where('status', 1);
    }

    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_ARGON2ID);
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
        // Display Name default value
        if (!isset($data['display_name'])) {
            $data['display_name'] = $data['username'];
        }
        $admin = Admin::create($data);
        if ($admin) {
            return $admin->id;
        } else {
            return false;
        }
    }

    public function deleteByID($id)
    {
        $admin = $this->find($id);
        if ($admin) {
            return $admin->delete();
        } else {
            return false;
        }
    }
}
