<?php

declare(strict_types=1);

namespace app\core\facade;

use app\core\BaseFacade;
use app\core\exception\SystemException;

class NullFacade extends BaseFacade
{
    public function isNull()
    {
        return true;
    }

    private function throwException()
    {
        throw new SystemException('invalid facade');
    }

    public function __call($name, $args)
    {
        $this->throwException();
    }

    public function __get($name)
    {
        $this->throwException();
    }
}
