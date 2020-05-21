<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\backend\logic;

use app\backend\model\AuthRule as AuthRuleModel;
use BlueM\Tree;
use BlueM\Tree\Serializer\HierarchicalTreeJsonSerializer;
<<<<<<< HEAD

class AuthRule extends AuthRuleModel
{
=======
use think\facade\Route;

class AuthRule extends AuthRuleModel
{

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
        $ifExists = $this->withTrashed()->where('rule', $data['rule'])->find();
        if ($ifExists) {
            $this->error = 'Sorry, that rule already exists.';
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

        $result = $this->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowTree)
                    ->select()
                    ->toArray();

        // Rename Key Name
<<<<<<< HEAD
        $result = array_map(function ($arr) {
            return [
                'id' => $arr['id'],
                'type' => $arr['type'],
                'parent' => $arr['parent_id'],
                'title' => $arr['name'],
                'key' => $arr['id'],
                'rule' => $arr['rule'],
                'condition' => $arr['condition'],
=======
        $result = array_map(function($arr) {
            return [
                'id'        =>  $arr['id'],
                'type'      =>  $arr['type'],
                'parent'    =>  $arr['parent_id'],
                'title'     =>  $arr['name'],
                'key'       =>  $arr['id'],
                'rule'      =>  $arr['rule'],
                'condition' =>  $arr['condition'],
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            ];
        }, $result);

        $serializer = new HierarchicalTreeJsonSerializer();

        $tree = new Tree($result, ['rootId' => 0, 'id' => 'id', 'parent' => 'parent', 'jsonSerializer' => $serializer]);

        return $tree;
    }
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
