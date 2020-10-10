<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Model as ModelLogic;

class Model extends ModelLogic
{
    public function designAPI($id)
    {
        $result = $this->field('data')->find($id);
        if ($result) {
            return resSuccess('', $result->toArray());
        } else {
            return resError('Target not found.');
        }
    }
}
