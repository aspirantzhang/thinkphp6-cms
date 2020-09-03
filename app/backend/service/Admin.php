<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;

class Admin extends AdminLogic
{
    public function listAPI($requestParams)
    {
        $data = $this->getPaginatedListData($requestParams, ['groups']);

        if ($data) {
            $addonData = [
                'groups' => arrayToTree($this->getAllGroups()),
                'status' => [0 => 'Disabled', 1 => 'Enabled']
            ];

            $layout = $this->buildList($addonData)->toArray();

            $layout['dataSource'] = $data['dataSource'];
            $layout['meta'] = $data['pagination'];

            return resSuccess('', $layout);
        } else {
            return resError('Get list failed.');
        }
    }

    public function addAPI()
    {
        $addonData = [
            'groups' => arrayToTree($this->getAllGroups()),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
        $page = $this->buildAdd($addonData)->toArray();
        
        if ($page) {
            return resSuccess('', $page);
        } else {
            return resError('Get page failed.');
        }
    }

    public function readAPI($id)
    {
        $admin = $this->where('id', $id)->with(['groups' => function ($query) {
            $query->where('auth_group.status', 1)->visible(['id']);
        }])->visible($this->allowRead)->find();

        if ($admin) {
            $admin = $admin->hidden(['groups.pivot'])->toArray();
            $admin['groups'] = extractFromAssocToIndexed($admin['groups'], 'id');

            $addonData = [
                'groups' => arrayToTree($this->getAllGroups()),
                'status' => [0 => 'Disabled', 1 => 'Enabled']
            ];

            $layout = $this->buildEdit($id, $addonData)->toArray();
            $layout['dataSource'] = $admin;

            return resSuccess('', $layout);
        } else {
            return resError('Admin not found.');
        }
    }


    public function updateAPI($id, $data)
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

    public function loginAPI($data)
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

    public function testAPI($params)
    {
        return $this->testList($params);
    }
}
