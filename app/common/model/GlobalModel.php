<?php

declare(strict_types=1);

namespace app\common\model;

use think\Model;

class GlobalModel extends Model
{
    protected $error = '';

    public function getError(): string
    {
        return $this->error ?: '';
    }
}
