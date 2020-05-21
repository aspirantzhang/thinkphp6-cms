<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\backend\logic;

use app\backend\model\AuthGroup as AuthGroupModel;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;

class AuthGroup extends AuthGroupModel
{
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    protected function getListData($data)
    {
        $search = getSearchParam($data, $this->allowSearch);
        $sort = getSortParam($data, $this->allowSort);
        $perPage = getPerPageParam($data);

        return $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage);
    }

    public function saveNew($data)
    {
        $ifExists = $this->withTrashed()->where('name', $data['name'])->find();
        if ($ifExists) {
            $this->error = 'Sorry, that name already exists.';
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return -1;
        }
        $result = $this->allowField($this->allowSave)->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return 0;
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
<<<<<<< HEAD
        $data = array_map(function ($arr) {
            return [
                'id' => $arr['id'],
                'parent' => $arr['parent_id'],
                'title' => $arr['name'],
                'key' => $arr['id'],
=======
        $data = array_map(function($arr) {
            return [
                'id'        =>  $arr['id'],
                'parent'    =>  $arr['parent_id'],
                'title'     =>  $arr['name'],
                'key'       =>  $arr['id']
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            ];
        }, $data);

        $serializer = new HierarchicalTreeJsonSerializer();

        $tree = new Tree($data, ['rootId' => 0, 'id' => 'id', 'parent' => 'parent', 'jsonSerializer' => $serializer]);

        return $tree;
    }
<<<<<<< HEAD
=======


>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
