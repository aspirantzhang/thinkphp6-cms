<?php

declare(strict_types=1);

namespace app\backend\logic;

use app\backend\model\Admin as AdminModel;
use app\backend\service\AuthGroup;

class Admin extends AdminModel
{
    protected function saveNew($data)
    {
        if ($this->checkUniqueFields($data) === false) {
            return false;
        }

        $data['display_name'] = $data['display_name'] ?? $data['username'];
        $data['groups'] = $data['groups'] ?? [];
        
        $this->startTrans();
        try {
            $this->save($data);
            $this->groups()->saveAll($data['groups']);
            $this->commit();
            return $this->getData('id');
        } catch (\Exception $e) {
            $this->rollback();
            $this->error = 'Save failed.';
            return false;
        }
    }

    public function checkPassword($data)
    {
        $admin = $this->where('username', $data['username'])->where('status', 1)->find();
        if ($admin) {
            return password_verify($data['password'], $admin->password);
        } else {
            return -1;
        }
    }
}
