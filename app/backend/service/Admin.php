<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function listApi($params)
    {
        $page = $this->buildList($params, ['groups' => arrayToTree($this->getAllGroups())])->toArray();
        $data = $this->getListData($params)->toArray();

        if ($data) {
            $result = $page;
            $result['dataSource'] = $data['dataSource'];
            $result['meta'] = $data['pagination'];
            return resSuccess('', $result);
        } else {
            return resError('Get list failed.');
        }
    }

    public function addApi()
    {
        $page = $this->buildAdd(['groups' => arrayToTree($this->getAllGroups())])->toArray();
        if ($page) {
            return resSuccess('', $page);
        } else {
            return resError('Get page failed.');
        }
    }

    public function saveApi($data)
    {
        $result = $this->saveNew($data);
        if ($result) {
            return resSuccess('Add successfully.');
        } else {
            return resError($this->error);
        }
    }

    public function readApi($id)
    {
        $admin = $this->where('id', $id)->with(['groups' => function ($query) {
            $query->where('auth_group.status', 1)->visible(['id']);
        }])->visible($this->allowRead)->find();

        $admin = $admin->hidden(['groups.pivot'])->toArray();

        $groupsArr = [];
        if (!empty($admin['groups'])) {
            foreach ($admin['groups'] as $group) {
                $groupsArr[] = $group['id'];
            }
        }
        $admin['groups'] = $groupsArr;

        if ($admin) {
            $list = $this->buildInner($id, ['groups' => arrayToTree($this->getAllGroups())])->toArray();
            $data = $admin;
            // $data = $admin->toArray();

            $result = $list;
            $result['dataSource'] = $data;

            return resSuccess('', $result);
        } else {
            return resError('Admin not found.');
        }
    }


    public function updateApi($id, $data)
    {
        $admin = $this->where('id', $id)->find();
        if ($admin) {
            $admin->startTrans();
            try {
                $admin->groups()->detach();
                if (count($data['groups'])) {
                    $admin->groups()->attach($data['groups']);
                }
                $admin->allowField($this->allowUpdate)->save($data);
                $admin->commit();
                return resSuccess('Update successfully.');
            } catch (\Exception $e) {
                $admin->rollback();
                return resError('Update failed.');
            }
        } else {
            return resError('Admin not found.');
        }
    }

    public function deleteApi($id)
    {
        $admin = $this->find($id);
        if ($admin) {
            if ($admin->delete()) {
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Admin not found.');
        }
    }

    public function loginApi($data)
    {
        $result = $this->checkPassword($data);
        if (-1 === $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } elseif (false == $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } else {
            return ['status' => 'ok', 'type' => 'account', 'currentAuthority' => 'admin'];
        }
    }
}
