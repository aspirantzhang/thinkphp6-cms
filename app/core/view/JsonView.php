<?php

declare(strict_types=1);

namespace app\core\view;

class JsonView
{
    public static function render($data)
    {
        halt('JsonView Handled', $data);
    }
}
