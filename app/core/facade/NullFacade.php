<?php

declare(strict_types=1);

namespace app\core\facade;

use app\core\CoreFacade;
use app\core\exception\SystemException;

class NullFacade extends CoreFacade
{
    public function isNull()
    {
        return true;
    }

    private function throwException(): never
    {
        throw new SystemException('invalid facade');
    }

    public function __call($name, $args)
    {
        $this->throwException();
    }

    public static function __callStatic($name, $args)
    {
        (new self())->throwException();
    }

    public function __get($name)
    {
        $this->throwException();
    }
}
