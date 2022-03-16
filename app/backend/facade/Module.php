<?php

declare(strict_types=1);

namespace app\backend\facade;

use app\backend\model\Module as ModuleModel;
use app\core\exception\SystemException;
use app\core\facade\CoreFacade;

class Module extends CoreFacade
{
    public function getModule(string $moduleName): ModuleModel
    {
        $module = $this->model->where('table_name', $moduleName)->find();
        if ($module) {
            return $module;
        }
        throw new SystemException('can not get module: ' . $moduleName);
    }
}
