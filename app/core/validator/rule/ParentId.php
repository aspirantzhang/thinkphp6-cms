<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use app\core\validator\BaseRule;
use think\facade\Request;

class ParentId extends BaseRule
{
    public function rule($value): bool
    {
        return (int) $value !== (int) Request::param('id');
    }
}
