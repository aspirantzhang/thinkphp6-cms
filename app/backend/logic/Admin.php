<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\Admin as AdminModel;
use app\backend\service\AuthGroup;

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

        return $this->with(['groups' => function ($query) {
                        $query->where('auth_group.status', 1)->hidden(['pivot']);
        }])
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

        $this->startTrans();
        try {
            $this->save($data);
            if (count($data['groups'])) {
                $this->groups()->attach($data['groups']);
            }
            $this->commit();
            return $this->getData('id');
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Save failed.';
            return false;
        }
    }

    protected function getAllGroups()
    {
        $group = new AuthGroup();
        $groupsData = $group->treeDataApi();
        $groupsData = array_map(function ($group) {
            return array(
                'id' => $group['id'],
                'key' => $group['id'],
                'value' => $group['id'],
                'title' => $group['name'],
                'parent_id' => $group['parent_id'],
            );
        }, $groupsData);

        return $groupsData;
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
