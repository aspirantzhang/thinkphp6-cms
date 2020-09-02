<?php

declare(strict_types=1);

namespace app\backend\model;

use app\common\model\GlobalModel;
use app\backend\traits\ModelLogic as ModelLogicTrait;
use app\backend\traits\ModelService as ModelServiceTrait;

class Common extends GlobalModel
{
    use ModelLogicTrait;
    use ModelServiceTrait;

    public function initialize()
    {
        parent::initialize();
    }
}
