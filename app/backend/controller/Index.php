<?php
declare (strict_types = 1);

namespace app\backend\controller;

use app\backend\controller\Common;
use app\backend\model\Admin as AdminModel;

class Index extends Common
{
    public function initialize()
    {
        parent::initialize();
    }
    public function index()
    {
        return 'backend controller index';
    }
    public function login(AdminModel $admin)
    {
        if ($admin->loginCheck($this->request->only(['username', 'password']))) {
            return json(['status'=>'ok', 'type'=>'account', 'currentAuthority'=>'admin']);
        } else {
            return json(['status'=>'error', 'type'=>'account', 'currentAuthority'=>'guest']);
        }
    }


}
