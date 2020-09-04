<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\AuthGroup as AuthGroupLogic;

class AuthGroup extends AuthGroupLogic
{

    public function getAddonData()
    {
        return [
            'parent_id' => arrayToTree($this->getParentData(), -1),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
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
}
