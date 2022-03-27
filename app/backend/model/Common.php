<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use app\core\model\Model;
use app\core\traits\ModuleInfo;

abstract class Common extends GlobalModel implements Model
{
    use ModuleInfo;
}
