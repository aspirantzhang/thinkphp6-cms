<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use app\core\model\Model;
use app\backend\model\Module;
use app\backend\facade\Module as ModuleFacade;
use think\helper\Str;

abstract class Common extends GlobalModel implements Model
{
    protected $deleteTime = 'delete_time';

    public function getTableName(): string
    {
        return Str::snake($this->name);
    }

    public function getModule(): Module
    {
        return (new ModuleFacade())->getModule($this->getTableName());
    }
}
