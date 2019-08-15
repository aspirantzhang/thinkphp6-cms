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
    protected $allowSearch = ['id', 'username', 'display_name', 'status', 'create_time'];
    protected $allowSort = ['id', 'create_time'];
    protected $allowRegister = [];
    protected $allowHidden = [];

    public function setPasswordAttr($value)
    {
        return password_hash($value, PASSWORD_ARGON2ID);
    }


    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }
    public function searchUsernameAttr($query, $value, $data)
    {
        $query->where('username', 'like', '%'. $value . '%');
    }
    public function searchDisplayNameAttr($query, $value, $data)
    {
        $query->where('display_name', 'like', '%'. $value . '%');
    }
    public function searchStatusAttr($query, $value, $data)
    {
        $query->where('status', $value);
    }
    public function searchCreateTimeAttr($query, $value, $data)
    {
        $timeArray = explode('T', $value);
        if (validateDateTime($timeArray[0]) && validateDateTime($timeArray[1])) {
            $query->whereBetweenTime('create_time', $timeArray[0], $timeArray[1]);
        }
    }


    public function list($data)
    {
        $list = $this->buildCondition($data);
        $searchFilter = array_intersect_key($data, array_flip($this->allowSearch));
        return $this->withSearch(array_keys($searchFilter), $searchFilter)->order($list['sort'], $list['order'])->hidden(['password', 'delete_time'])->paginate($list['per_page']);
    }

    public function buildCondition($data)
    {
        $list['sort'] = 'id';
        $list['order'] = 'desc';
        $list['per_page'] = 10;

        if (isset($data['sort'])) {
            $list['sort'] = in_array($data['sort'], $this->allowSort) ? $data['sort'] : 'id';
        }
        if (isset($data['order'])) {
            $list['order'] = ($data['order'] == 'asc') ? 'asc' : 'desc';
        }
        if (isset($data['per_page'])) {
            $list['per_page'] = $data['per_page'];
        }
        return $list;
    }

    public function loginCheck($data)
    {
        $admin = $this->where('username', $data['username'])->where('status', 1)->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return false;
        }
    }

    public function saveNew($data)
    {
        $adminExist = $this->where('username', $data['username'])->find();
        if ($adminExist) {
            $this->error = 'Sorry, that username already exists.';
            return -1;
        }
        // Display Name default value
        if (!isset($data['display_name'])) {
            $data['display_name'] = $data['username'];
        }
        $result = $this->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
            return 0;
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
