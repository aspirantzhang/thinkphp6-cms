<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\AuthGroup as AuthGroupModel;

class AuthGroup extends AuthGroupModel
{
    protected function getAddonData()
    {
        return [
            'parent_id' => arrayToTree($this->getParentData(), -1),
            'status' => [0 => 'Disabled', 1 => 'Enabled']
        ];
    }
}
