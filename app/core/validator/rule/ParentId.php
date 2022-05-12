<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use app\core\validator\CoreRule;
use think\facade\Request;
use think\Validate;

class ParentId implements CoreRule
{
    public function handle(Validate $validate)
    {
        $validate->extend('ParentId', function ($value) {
            return $value !== Request::param('id');
        });
    }
}
