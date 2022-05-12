<?php

declare(strict_types=1);

namespace app\core\validator;

use think\Validate;

interface CoreRule
{
    public function handle(Validate $validate);

    public function check($value): bool;
}
