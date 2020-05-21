<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\AuthGroup as AuthGroupLogic;

class AuthGroup extends AuthGroupLogic
{
    public function listApi($data)
    {
        $list = $this->buildList();
        $dataSource = $this->getListData($data)->toArray();
        $list['table']['dataSource'] = $dataSource['dataSource'];
        $list['table']['pagination'] = $dataSource['pagination'];

        return $list;
    }

    public function createApi()
    {
        $form = $this->buildSingle();

        return $form;
    }

    public function saveApi($data)
    {
        $result = $this->saveNew($data);
        if (-1 == $result) {
            //already exists
            return $this->error($this->error);
        } elseif (0 == $result) {
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

            return $form;
        } else {
            return $this->error('Group not found');
        }
    }

    public function editApi($id)
    {
        $result = $this->where('id', $id)->find();
        if ($result) {
            $result = $result->visible($this->allowRead)->toArray();
            $form = $this->buildSingle($result, 'edit');

            return $form;
        } else {
            return $this->error('Group not found');
        }
    }

    public function updateApi($id, $data)
    {
        $group = $this->where('id', $id)->find();
        if ($group) {
            if ($group->allowField($this->allowUpdate)->save($data)) {
                return $this->success('Update completed successfully.');
            } else {
                return $this->error('Update failed.');
            }
        } else {
            return $this->error('Group not found.');
        }
    }

    public function deleteApi($id)
    {
        $admin = $this->find($id);
        if ($admin) {
            if ($admin->delete()) {
                return $this->success('Delete completed successfully.');
            } else {
                return $this->error('Delete failed.');
            }
        } else {
            return $this->error('Group not found.');
        }
    }

    public function printTree($data)
    {
        return $this->getTreeList($data);
    }
}
