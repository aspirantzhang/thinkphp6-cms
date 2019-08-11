<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::post('login', 'Index/login')->validate(\app\backend\validate\Admin::class, 'login')->allowCrossDomain();

Route::resource('admins', 'Admin')->except(['create', 'edit'])
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\Admin::class)->allowCrossDomain();


