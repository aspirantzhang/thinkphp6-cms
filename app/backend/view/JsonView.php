<?php

declare(strict_types=1);

namespace app\backend\view;

class JsonView
{
    public static function render($data)
    {
        halt('JsonView Handled', $data);
    }
}
