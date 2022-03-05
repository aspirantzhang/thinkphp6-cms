<?php

declare(strict_types=1);

namespace app\core\facade;

use app\core\Facade;

abstract class CoreFacade extends Facade
{
    protected $model;
    public bool $isNull = false;

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
    }
}
