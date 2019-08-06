<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }
    public function index()
    {
        var_dump($this->request->param('name'));
    }
}
