<?php

declare(strict_types=1);

namespace app\core\model;

use app\backend\model\Module;

interface Model
{
    public function getTableName(): string;

    public function getModule(string $itemName = null): mixed;
}
