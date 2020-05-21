<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function listApi($data)
    {
<<<<<<< HEAD
        $list = $this->buildList()->toArray();
        $listData = $this->getListData($data)->toArray();

        $list['success'] = true;
        $list['message'] = '';
        $list['data']['dataSource'] = $listData['dataSource'];
        $list['data']['meta'] = $listData['pagination'];

=======
        $list = $this->buildList();
        $dataSource = $this->getListData($data)->toArray();
        $list['table']['dataSource'] = $dataSource['dataSource'];
        $list['table']['pagination'] = $dataSource['pagination'];
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
        $result = $this->where('id', $id)->with('groups')->find();
        if ($result) {
            $form = $result->visible($this->allowRead)->toArray();
<<<<<<< HEAD

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
            return $form;
        } else {
            return $this->error('Admin not found');
        }
    }

    public function editApi($id)
    {
<<<<<<< HEAD
        $result = $this->where('id', $id)->with(['groups' => function ($query) {
            $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
        }])->find();
        if ($result) {
            $result = $result->visible($this->allowRead)->toArray();
            $form = $this->buildSingle($result, 'edit');

=======
        $result = $this->where('id', $id)->with(['groups'=>function($query) {
                        $query->field('auth_group.id, auth_group.name')->where('auth_group.status', 1)->hidden(['pivot']);
                    }])->find();
        if ($result) {
            $result = $result->visible($this->allowRead)->toArray();
            $form = $this->buildSingle($result, 'edit');
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
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
<<<<<<< HEAD
        if (-1 === $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } elseif (false == $result) {
            return ['status' => 'error', 'type' => 'account', 'currentAuthority' => 'guest'];
        } else {
            return ['status' => 'ok', 'type' => 'account', 'currentAuthority' => 'admin'];
        }
    }
=======
        if ($result === -1) {
            return ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest'];
        } elseif ($result == false) {
            return ['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest'];
        } else {
            return ['status'=>'ok', 'type'=>'account', 'currentAuthority'=>'admin'];
        }

    }

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
}
