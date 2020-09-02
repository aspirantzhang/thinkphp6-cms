<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\AuthGroup as AuthGroupLogic;

class AuthGroup extends AuthGroupLogic
{
    public function treeListAPI($requestParams)
    {
        $data = $this->getAllData($requestParams);

        if ($data) {
            $addonData = [
                'status' => [0 => 'Disabled', 1 => 'Enabled']
            ];

            $layout = $this->buildList($addonData)->toArray();

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
        $addonData = [
            'parent_id' => arrayToTree($this->getParentData(), -1),
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
        $group = $this->where('id', $id)->find();
        if ($group) {
            $group = $group->visible($this->allowRead)->toArray();

            $addonData = [
                'parent_id' => arrayToTree($this->getParentData($id), -1),
                'status' => [0 => 'Disabled', 1 => 'Enabled']
            ];
            
            $layout = $this->buildEdit($id, $addonData)->toArray();
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

    public function batchDeleteAPI($idArray)
    {
        if (count($idArray)) {
            $result = $this->whereIn('id', $idArray)->select()->delete();
            if ($result) {
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Nothing to do.');
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
