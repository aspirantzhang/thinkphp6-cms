<?php

declare(strict_types=1);

namespace app\backend\model;

use app\backend\facade\Module as ModuleFacade;
use app\common\model\GlobalModel;
use app\core\exception\SystemException;
use app\core\model\Model;
use think\helper\Str;

abstract class Common extends GlobalModel implements Model
{
    protected $deleteTime = 'delete_time';

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
            if (!isset($field[$itemName])) {
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
            if (!isset($operation[$itemName])) {
                throw new SystemException('cannot find that item in module operation: ' . $this->getTableName() . '-> operation -> ' . $itemName);
            }

            return $operations[$itemName];
        }

        return $operations;
    }
}
