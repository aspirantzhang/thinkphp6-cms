<?php

declare(strict_types=1);

namespace app\core\model;

interface Model
{
    public function getTableName(): string;

    public function getModule(string $itemName = null): mixed;

    public function getModuleField(string $itemName = null): mixed;

    public function getModuleOperation(string $itemName = null): mixed;

    public function getFieldSetWithSpecificProperty(string $propertyName): array;

    public function getAllowBrowse(): array;

    public function getAllowRead(): array;

    public function getAllowAdd(): array;

    public function getAllowEdit(): array;

    public function getTranslate(): array;

    public function getUnique(): array;
}
