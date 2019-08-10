<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\model\Admin;

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }
    public function index()
    {
        echo 'backend index';
    }
    public function login(Admin $admin)
    {
        if ($admin->loginCheck($this->request->only(['username', 'password']))) {
            return json(['status'=>'ok', 'type'=>'account', 'currentAuthority'=>'admin']);
        } else {
            return json(['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest']);
        }
    }


}
