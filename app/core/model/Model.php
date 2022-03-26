<?php

declare(strict_types=1);

namespace app\core\model;

interface Model
{
    public function getTableName(): string;

    public function getModule(string $itemName = null): mixed;

    public function getModuleField(string $itemName = null): mixed;
}
