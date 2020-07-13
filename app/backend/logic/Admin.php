<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\Admin as AdminModel;

class Admin extends AdminModel
{
    protected function getListData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);
        $perPage = getPerPageParam($data);

        // return $this->with(['groups' => function ($query) {
        //     $query->field('auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
        // }])

        return $this->with(['groups'])
                    ->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    protected function ifExists($username)
    {
        $result = $this->withTrashed()->where('username', $username)->find();
        if ($result) {
            return false;
        } else {
            return true;
        }
    }

    protected function saveNew($data)
    {
        if ($this->ifExists($data['username']) === false) {
            $this->error = 'The username already exists.';
            return false;
        }
        
        $data['display_name'] = $data['display_name'] ?? $data['username'];

        $result = $this->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
            return false;
        }
    }

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
