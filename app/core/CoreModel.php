<?php

declare(strict_types=1);

namespace app\core;

use app\common\model\GlobalModel;
use app\core\traits\ModuleInfo;

abstract class CoreModel extends GlobalModel
{
    use ModuleInfo;

    protected $deleteTime = 'delete_time';
}
