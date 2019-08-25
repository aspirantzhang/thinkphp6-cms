<?php
declare (strict_types = 1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function listApi($data)
    {
        $list = $this->buildPageIndex();
        $dataSource = $this->getNormalList($data)->toArray();
        $list['table']['dataSource'] = $dataSource['dataSource'];
        $list['table']['pagination'] = $dataSource['pagination'];
        return $list;
    }

    public function createApi()
    {
        $form = $this->buildPageCreate();
        return $form;
    }

    public function saveApi($data)
    {
        $result = $this->saveNew($data);
        if ($result == -1) {
            return msg(4091, $this->error);
        } elseif ($result == 0) {
            return msg(4001, $this->error);
        } else {
            return msg(201);
        }
    }

    public function readApi($id)
    {
        $result = $this->where('id', $id)->find();
        if ($result) {
            return msg(200, $result->visible($this->allowRead));
        } else {
            return msg(4041, 'Admin not found.');
        }
    }

    public function editApi()
    {
        $form = $this->buildPageEdit();
        return $form;
    }

    public function updateApi($id, $data)
    {
        $admin = $this->where('id', $id)->find();
        if ($admin) {
            if ($admin->allowField($this->allowUpdate)->save($data)) {
                return msg(204);
            } else {
                return msg(4092, 'Update failed.');
            }
        } else {
            return msg(4041, 'Admin not found.');
        }
    }

    public function deleteApi($id)
    {
        $admin = $this->find($id);
        if ($admin) {
            if ($admin->delete()) {
                return msg(204);
            } else {
                return msg(4093, 'Delete failed.');
            }
        } else {
            return msg(4101, 'That Admin Does not exist.');
        }
    }

    public function loginApi($data)
    {
        $result = $this->checkPassword($data);
        if ($result === -1) {
            return msg(200, ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest']);
        } elseif ($result == false) {
            return msg(200, ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest']);
        } else {
            return msg(200, ['status'=>'ok', 'type'=>'account', 'currentAuthority'=>'admin']);
        }

    }

}
