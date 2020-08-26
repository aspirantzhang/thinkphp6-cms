<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\AuthGroup as AuthGroupLogic;

class AuthGroup extends AuthGroupLogic
{
    public function treeListAPI($params)
    {
        $data = $this->getAllData($params);

        if ($data) {
            $layout = $this->buildList($params)->toArray();

            $layout['dataSource'] = arrayToTree($data);
            $layout['meta'] = [
                'total' => 0,
                'per_page' => 10,
                'page' => 1,
            ];

            return resSuccess('', $layout);
        } else {
            return resError('Get list failed.');
        }
    }

    public function treeDataAPI($params = [])
    {
        return $this->getAllData($params);
    }

    public function addAPI()
    {
        $page = $this->buildAdd(['parent' => arrayToTree($this->getParentData(), -1)])->toArray();

        if ($page) {
            return resSuccess('', $page);
        } else {
            return resError('Get page failed.');
        }
    }

    public function saveAPI($data)
    {
        $result = $this->saveNew($data);
        if ($result) {
            return resSuccess('Add successfully.');
        } else {
            return resError($this->error);
        }
    }

    public function readAPI($id)
    {
        $group = $this->where('id', $id)->find();
        if ($group) {
            $group = $group->visible($this->allowRead)->toArray();
            $layout = $this->buildEdit($id, ['parent' => arrayToTree($this->getParentData($id), -1)])->toArray();

            $layout['dataSource'] = $group;

            return resSuccess('', $layout);
        } else {
            return resError('Group not found.');
        }
    }

    public function updateAPI($id, $data)
    {
        $group = $this->where('id', $id)->find();
        if ($group) {
            if ($group->allowField($this->allowUpdate)->save($data)) {
                return resSuccess('Update successfully.');
            } else {
                return resError('Update failed.');
            }
        } else {
            return resError('Group not found.');
        }
    }

    public function deleteAPI($id)
    {
        $group = $this->find($id);
        if ($group) {
            if ($group->delete()) {
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Group not found.');
        }
    }

    public function getUserIDsByGroups(array $groupIDs = []): array
    {
        $groups = $this->whereIn('id', $groupIDs)->with(['admins'])->hidden(['admins.pivot'])->select();

        if (!$groups->isEmpty()) {
            $adminIDs = extractUniqueValuesInArray($groups->toArray(), 'admins', 'id');
            return $adminIDs;
        }

        return [];
    }

    public function getParentAPI()
    {
        return $this->getParentData();
    }

    public function testAPI()
    {
        return $this->getAllData([]);
    }
}
