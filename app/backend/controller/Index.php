<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Admin as AdminService;

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
