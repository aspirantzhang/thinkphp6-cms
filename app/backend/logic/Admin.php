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

    public function saveNew($data)
    {
        $ifExists = $this->withTrashed()->where('username', $data['username'])->find();
        if ($ifExists) {
            $this->error = 'Sorry, that username already exists.';

            return -1;
        }
        // Display Name default value
        if (!isset($data['display_name'])) {
            $data['display_name'] = $data['username'];
        }
        $result = $this->allowField($this->allowSave)->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';

            return 0;
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
