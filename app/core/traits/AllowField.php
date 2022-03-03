<?php

declare(strict_types=1);

namespace app\core\traits;

use think\helper\Str;

trait AllowField
{
    public function getAllow(string $action): array
    {
        $modelClass = str_replace('controller', 'model', static::class);
        $configKeyName = 'allow' . Str::studly($action);
        if (class_exists($modelClass) && isset($modelClass::$config[$configKeyName])) {
            return $this->request->only($modelClass::$config[$configKeyName] ?? []);
        }
        return [];
    }
}
