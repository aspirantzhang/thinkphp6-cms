<?php

declare(strict_types=1);

namespace app\core\validator;

use think\Validate;

abstract class BaseRule
{
    public function __construct(private Validate $validate)
    {
    }

    public function check()
    {
        $this->validate->extend(class_basename(static::class), function ($value) {
            return $this->rule($value);
        });
    }

    abstract public function rule($value): bool;
}
