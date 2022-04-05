<?php

declare(strict_types=1);

namespace app\core;

use app\core\exception\SystemException;
use think\db\Query;

abstract class CoreFacade
{
    protected CoreModel | Query $model;

    public function isNull()
    {
        return false;
    }

    public function __construct()
    {
        $this->initModel();
    }

    protected function initModel()
    {
        $modelClass = str_replace('facade', 'model', static::class);
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();

            return;
        }
        throw new SystemException('model cannot be instantiated: ' . $modelClass);
    }
}
