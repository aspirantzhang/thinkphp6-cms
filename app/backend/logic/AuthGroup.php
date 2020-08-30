<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\AuthGroup as AuthGroupModel;

class AuthGroup extends AuthGroupModel
{
    protected function getListData($requestParams)
    {
        $search = getSearchParam($requestParams, $this->allowSearch);
        $sort = getSortParam($requestParams, $this->allowSort);
        $perPage = getPerPageParam($requestParams);

        return $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    public function getAllData($requestParam = [])
    {
        $search = getSearchParam($requestParam, $this->allowSearch);
        $sort = getSortParam($requestParam, $this->allowSort);

        $normalListData = $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->select()
                    ->toArray();

        return $normalListData;
    }


    protected function saveNew($data)
    {
        $result = $this->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
            return false;
        }
    }

    public function getParentData($exceptID = 23)
    {
        $groupsData = $this->getAllData();
        $groupsData = array_map(function ($group) use ($exceptID) {
            if ($group['id'] != $exceptID) {
                return [
                    'id' => $group['id'],
                    'key' => $group['id'],
                    'value' => $group['id'],
                    'title' => $group['name'],
                    'parent_id' => $group['parent_id'],
                ];
            } else {
                return null;
            }
        }, $groupsData);

        $groupsData[] = [
            'id' => 0,
            'key' => 0,
            'value' => 0,
            'title' => 'Top',
            'parent_id' => -1,
        ];

        // filter null
        return array_filter($groupsData);
    }
}
