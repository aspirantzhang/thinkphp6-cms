<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function listApi($params)
    {
        $list = $this->buildList($params)->toArray();
        $data = $this->getListData($params)->toArray();

        $result['success'] = true;
        $result['message'] = '';
        $result['data'] = $list;
        $result['data']['dataSource'] = $data['dataSource'];
        $result['data']['meta'] = $data['pagination'];

        return $result;
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
        // $data = $this->getPageData($id)->toArray();

        $admin = $this->where('id', $id)->with('groups')->find();
        if ($admin) {
            $list = $this->buildPage($id)->toArray();
            $data = $admin->visible($this->allowRead)->toArray();

            $result['success'] = true;
            $result['message'] = '';
            $result['data'] = $list;
            $result['data']['dataSource'] = $data;

            return $result;
        } else {
            return $this->error('Admin not found');
        }

        // $result = $this->where('id', $id)->with('groups')->find();
        // if ($result) {
        //     $form = $result->visible($this->allowRead)->toArray();

        //     return $form;
        // } else {
        //     return $this->error('Admin not found');
        // }
    }

    public function editApi($id)
    {
        $result = $this->where('id', $id)->with(['groups' => function ($query) {
            $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
        }])->find();
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
        if (-1 === $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } elseif (false == $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } else {
            return ['status' => 'ok', 'type' => 'account', 'currentAuthority' => 'admin'];
        }
    }
}
