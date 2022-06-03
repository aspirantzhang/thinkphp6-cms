<?php

declare(strict_types=1);

namespace app\jwt\facade;

use think\Facade;

class App extends Facade
{
    protected static function getFacadeClass()
    {
        return 'app\jwt\JWT';
    }
}
