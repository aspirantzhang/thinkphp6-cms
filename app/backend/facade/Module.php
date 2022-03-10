<?php

declare(strict_types=1);

namespace app\backend\facade;

use app\core\facade\CoreFacade;
use app\backend\model\Module as ModuleModel;
use app\backend\SystemException;

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
