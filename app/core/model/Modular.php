<?php

declare(strict_types=1);

namespace app\core\model;

use app\backend\facade\Module as ModuleFacade;
use app\core\exception\SystemException;
use think\helper\Str;

trait Modular
{
    public function getTableName(): string
    {
        return Str::snake($this->name);
    }

    public function getModule(string $itemName = null): mixed
    {
        $module = (new ModuleFacade())->getModule($this->getTableName());
        if (!empty($itemName)) {
            if (!isset($module->$itemName)) {
                throw new SystemException('cannot find that item in module: ' . $this->getTableName() . '->' . $itemName);
            }

            return $module->getAttr($itemName);
        }

        return $module->toArray();
    }

    public function getModuleField(string $itemName = null): mixed
    {
        $fields = $this->getModule('field');

        if (empty($fields) || !is_array($fields)) {
            throw new SystemException('no fields founded in module: ' . $this->getTableName());
        }

        if (!empty($itemName)) {
            if (!isset($fields[$itemName])) {
                throw new SystemException('cannot find that item in module field: ' . $this->getTableName() . '-> field -> ' . $itemName);
            }

            return $fields[$itemName];
        }

        return $fields;
    }

    public function getModuleOperation(string $itemName = null): mixed
    {
        $operations = $this->getModule('operation');

        if (empty($operations) || !is_array($operations)) {
            throw new SystemException('no operations founded in module: ' . $this->getTableName());
        }

        if (!empty($itemName)) {
            if (!isset($operations[$itemName])) {
                throw new SystemException('cannot find that item in module operation: ' . $this->getTableName() . '-> operation -> ' . $itemName);
            }

            return $operations[$itemName];
        }

        return $operations;
    }

    /**
     * Get field list by specific property in the module.
     */
    public function getFieldListByProperty(string $propertyName): array
    {
        $result = [];

        foreach ($this->getModuleField() as $field) {
            if (str_contains($propertyName, '.')) {
                // e.g. allow.view
                [$prop, $action] = explode('.', $propertyName, 2);
                $haystack = $field[$prop] ?? [];
                if (in_array($action, $haystack)) {
                    $result[] = $field['name'];
                }
            } else {
                if (isset($field[$propertyName]) && $field[$propertyName] === true) {
                    $result[] = $field['name'];
                }
            }
        }

        return $result;
    }

    public function getDefaultConfig(string $type, string $property): array
    {
        $defaultAllowFile = createPath(dirname(__DIR__), 'config', $type) . '.php';
        if (file_exists($defaultAllowFile)) {
            $defaultAllow = require $defaultAllowFile;
        }

        return $defaultAllow[$property] ?? [];
    }

    public function getAllow(string $property): array
    {
        $default = $this->getDefaultConfig('allow', $property);
        $custom = $this->getFieldListByProperty('allow.' . $property);

        return [...$default, ...$custom];
    }

    public function getRequire(string $action): array
    {
        return $this->getFieldListByProperty('require.' . $action);
    }

    public function getUnique(): array
    {
        return $this->getFieldListByProperty('unique');
    }
}
