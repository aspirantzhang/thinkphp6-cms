<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use app\backend\traits\Model as ModelTrait;
use app\backend\traits\Logic as LogicTrait;
use app\backend\traits\Service as ServiceTrait;

class Common extends GlobalModel
{
    use ModelTrait;
    use LogicTrait;
    use ServiceTrait;

    public function initialize()
    {
        parent::initialize();
    }
}
