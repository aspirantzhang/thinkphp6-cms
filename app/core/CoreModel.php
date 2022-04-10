<?php

declare(strict_types=1);

namespace app\core;

use app\common\model\GlobalModel;
use app\core\model\Modular;

abstract class CoreModel extends GlobalModel
{
    use Modular;

    protected $deleteTime = 'delete_time';
}
