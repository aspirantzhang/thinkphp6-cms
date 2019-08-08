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
    public function __construct()
    {
    }
    public function index()
    {
        echo 'base ok';
    }

}
