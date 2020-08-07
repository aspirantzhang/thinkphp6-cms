<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\AuthGroup as AuthGroupModel;

class AuthGroup extends AuthGroupModel
{
    protected function getListData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);
        $perPage = getPerPageParam($data);

        // return $this->with(['groups' => function ($query) {
        //     $query->field('auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
        // }])

        return $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    public function getAllData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);

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
}
