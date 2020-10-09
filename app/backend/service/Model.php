<?php

declare(strict_types=1);

namespace app\backend\service;

use app\backend\logic\Model as ModelLogic;

class Model extends ModelLogic
{
    public function designAPI($id)
    {
        $result = $this->field('data')->find($id)->toArray();
        return resSuccess('', $result);
    }
}
