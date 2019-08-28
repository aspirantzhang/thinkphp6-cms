<?php
declare (strict_types = 1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
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
        if ($result == -1) {
            //already exists
            return $this->error($this->error);
        } elseif ($result == 0) {
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
            return $this->error('Admin not found');
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
            return $this->error('Admin not found');
        }
    }

    public function updateApi($id, $data)
    {
        $admin = $this->where('id', $id)->find();
        if ($admin) {
            if ($admin->allowField($this->allowUpdate)->save($data)) {
                return $this->success('Update completed successfully.');
            } else {
                return $this->error('Update failed.');
            }
        } else {
            return $this->error('Admin not found.');
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
            return $this->error('Admin not found.');
        }
    }

    public function loginApi($data)
    {
        $result = $this->checkPassword($data);
        if ($result === -1) {
            return ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest'];
        } elseif ($result == false) {
            return ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest'];
        } else {
            return ['status'=>'ok', 'type'=>'account', 'currentAuthority'=>'admin'];
        }

    }

}
