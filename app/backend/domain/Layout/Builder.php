<?php

declare(strict_types=1);

namespace app\backend\domain\Layout;

class Builder
{
    public static function field(string $name): Builder\Field
    {
        return new Builder\Field($name);
    }
}
