<?php

declare(strict_types=1);

namespace app\core;

use app\core\exception\SystemException;

abstract class CoreFacade
{
    protected CoreModel $model;

    public function __construct()
    {
        $this->initModel();
    }

    public function isNull()
    {
        return false;
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
