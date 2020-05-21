<?php
<<<<<<< HEAD

declare(strict_types=1);
=======
declare (strict_types = 1);
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

namespace app\backend\service;

use app\backend\logic\AuthRule as AuthRuleLogic;

class AuthRule extends AuthRuleLogic
{
    public function listApi($data)
    {
        $list = $this->buildList();
        $dataSource = $this->getListData($data)->toArray();
        $list['table']['dataSource'] = $dataSource['dataSource'];
        $list['table']['pagination'] = $dataSource['pagination'];
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return $list;
    }

    public function createApi()
    {
        $form = $this->buildSingle();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return $form;
    }

    public function saveApi($data)
    {
        $result = $this->saveNew($data);
<<<<<<< HEAD
        if (-1 == $result) {
            //already exists
            return $this->error($this->error);
        } elseif (0 == $result) {
=======
        if ($result == -1) {
            //already exists
            return $this->error($this->error);
        } elseif ($result == 0) {
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            // save failed
            return $this->error($this->error);
        } else {
            return $this->success('Create completed successfully.');
        }
    }

    public function readApi($id)
    {
        $result = $this->where('id', $id)->find();
        if ($result) {
            $form = $result->visible($this->allowRead)->toArray();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return $form;
        } else {
            return $this->error('Rule not found');
        }
    }

    public function editApi($id)
    {
        $result = $this->where('id', $id)->find();
        if ($result) {
            $result = $result->visible($this->allowRead)->toArray();
            $form = $this->buildSingle($result, 'edit');
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return $form;
        } else {
            return $this->error('Rule not found');
        }
    }

    public function updateApi($id, $data)
    {
        $rule = $this->where('id', $id)->find();
        if ($rule) {
            if ($rule->allowField($this->allowUpdate)->save($data)) {
                return $this->success('Update completed successfully.');
            } else {
                return $this->error('Update failed.');
            }
        } else {
            return $this->error('Rule not found.');
        }
    }

    public function deleteApi($id)
    {
        $rule = $this->find($id);
        if ($rule) {
            if ($rule->delete()) {
                return $this->success('Delete completed successfully.');
            } else {
                return $this->error('Delete failed.');
            }
        } else {
            return $this->error('Rule not found.');
        }
    }
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    public function printTree($data)
    {
        return $this->getTreeList($data);
    }

    public function menuApi()
    {
<<<<<<< HEAD
        return $this->getTreeList(['is_menu' => 1, 'order' => 'asc']);
    }
=======
        return $this->getTreeList(['is_menu'=>1 , 'order'=>'asc']);
    }

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
