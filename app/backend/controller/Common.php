<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\common\controller\GlobalController;
use app\backend\view\JsonView;
use app\backend\SystemException;

class Common extends GlobalController
{
    public function initialize()
    {
        parent::initialize();
    }

    public function getAllow(string $action): array
    {
        $modelClass = str_replace('controller', 'model', static::class);
        if (class_exists($modelClass)) {
            return $this->request->only($modelClass::$config[$action] ?? []);
        }
        return [];
    }

    public function facade()
    {
        $facadeClass = str_replace('controller', 'facade', static::class);
        if (class_exists($facadeClass)) {
            return new $facadeClass();
        }
        throw new SystemException('unknown facade');
    }

    public function jsonView()
    {
        return new JsonView();
    }
}
