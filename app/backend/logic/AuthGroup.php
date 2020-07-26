<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\AuthGroup as AuthGroupModel;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;

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

    public function getTreeList($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);

        $data = $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowTree)
                    ->select()
                    ->toArray();

        // Rename Key Name
        $data = array_map(function ($arr) {
            return [
                'id' => $arr['id'],
                'parent' => $arr['parent_id'],
                'title' => $arr['name'],
                'key' => $arr['id'],
            ];
        }, $data);

        $serializer = new HierarchicalTreeJsonSerializer();

        $tree = new Tree($data, ['rootId' => 0, 'id' => 'id', 'parent' => 'parent', 'jsonSerializer' => $serializer]);

        return $tree;
    }
}
