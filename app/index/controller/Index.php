<?php

namespace app\index\controller;

use app\BaseController;
use think\facade\View;

class Index extends BaseController
{
    public function admins()
    {
        return View::fetch('test', ['username' => 'zhang']);
    }

    public function test()
    {
        return 'test';
    }

    public function page404()
    {
        return View::fetch('404');
    }
}
