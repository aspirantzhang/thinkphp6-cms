<?php
declare (strict_types = 1);

namespace app\backend\service;

use app\backend\logic\AuthGroup as AuthGroupLogic;

class AuthGroup extends AuthGroupLogic
{
    public function listApi($data)
    {
        return $this->getNormalList($data);
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
            return msg(4041, 'Group not found.');
        }
    }

    public function updateApi($id, $data)
    {
        $group = $this->where('id', $id)->find();
        if ($group) {
            if ($group->allowField($this->allowUpdate)->save($data)) {
                return msg(204);
            } else {
                return msg(4092, 'Update failed.');
            }
        } else {
            return msg(4041, 'Group not found.');
        }
    }

    public function deleteApi($id)
    {
        $group = $this->find($id);
        if ($group) {
            if ($group->delete()) {
                return msg(204);
            } else {
                return msg(4093, 'Delete failed.');
            }
        } else {
            return msg(4101, 'That Group Does not exist.');
        }
    }

    public function printTree($data)
    {
        return $this->getTreeList($data);
    }


}
