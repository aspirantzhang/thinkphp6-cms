<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\Admin as AdminService;

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }

    public function home()
    {
        return 'backend controller index';
    }
}
