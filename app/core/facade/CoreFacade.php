<?php

declare(strict_types=1);

namespace app\core\facade;

use app\core\Facade;

abstract class CoreFacade extends Facade
{
    public bool $isNull = false;
}
