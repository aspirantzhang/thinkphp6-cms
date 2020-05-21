<?php

declare(strict_types=1);

namespace app\common\model;

use think\Model;

class GlobalModel extends Model
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getError()
    {
        return $this->error;
    }
}
