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
}
