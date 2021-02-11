<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;

class Model extends ModelLogic
{
    public function designAPI($id)
    {
        $result = $this->field('data')->find($id);
        if ($result) {
            return $this->success('', $result->toArray());
        } else {
            return $this->error('Target not found.');
        }
    }
}
