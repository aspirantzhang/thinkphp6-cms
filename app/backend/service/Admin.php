<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Admin as AdminLogic;
use app\backend\service\AuthGroup;

class Admin extends AdminLogic
{
    public function getAddonData()
    {
        return [
            'groups' => $this->getModelTreeData(new AuthGroup()),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }

    public function readAPI($id)
    {
        $admin = $this->where('id', $id)->with(['groups' => function ($query) {
            $query->where('auth_group.status', 1)->visible(['id']);
        }])->visible($this->allowRead)->find();

        if ($admin) {
            $admin = $admin->hidden(['groups.pivot'])->toArray();
            $admin['groups'] = extractFromAssocToIndexed($admin['groups'], 'id');


            $layout = $this->buildEdit($id, $this->getAddonData())->toArray();
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
}
