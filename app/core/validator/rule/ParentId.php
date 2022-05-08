<?php

declare(strict_types=1);

namespace app\core\validator\rule;

use think\facade\Request;
use think\Validate;

class ParentId implements \SplObserver
{
    public function update(\SplSubject | Validate $validate): void
    {
        $validate->extend('ParentId', function ($value) {
            return $value !== Request::param('id');
        });
    }
}
