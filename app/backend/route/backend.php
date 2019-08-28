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

Route::post('login', 'Index/login')->validate(\app\backend\validate\Admin::class, 'login')->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);

Route::resource('admins', 'Admin')
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\Admin::class)
        ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);

Route::get('admins/:id/groups', 'Admin/groups')
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\Admin::class)
        ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);


Route::resource('groups', 'AuthGroup')
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthGroup::class)
        ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);

Route::get('groups/tree', 'AuthGroup/tree')
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthGroup::class)
        ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);

Route::resource('rules', 'AuthRule')
        ->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthRule::class)
        ->allowCrossDomain([
        'Access-Control-Allow-Origin'        => 'http://localhost:8000',
        'Access-Control-Allow-Credentials'   => 'true'
    ]);

