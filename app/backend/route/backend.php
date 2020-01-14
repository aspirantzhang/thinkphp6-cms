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


Route::group(function () {

    Route::post('login', 'index/login')->validate(\app\backend\validate\Admin::class, 'login');

    Route::group('admins', function() {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::get('read/:id', 'read');
        Route::get(':id/groups', 'groups');
        Route::get('edit/:id', 'edit');
        Route::post('save', 'save');
        Route::post('update/:id', 'update');
        Route::post('delete/:id', 'delete');
    })->prefix('admin/')->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\Admin::class);

    Route::group('groups', function() {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::get('read/:id', 'read');
        Route::get('edit/:id', 'edit');
        Route::get('tree', 'tree');
        Route::post('save', 'save');
        Route::post('update/:id', 'update');
        Route::post('delete/:id', 'delete');
    })->prefix('auth_group/')->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthGroup::class);

    Route::group('rules', function() {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::get('read/:id', 'read');
        Route::get('edit/:id', 'edit');
        Route::get('menus', 'menus');
        Route::post('save', 'save');
        Route::post('update/:id', 'update');
        Route::post('delete/:id', 'delete');
    })->prefix('auth_rule/')->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthRule::class);

})->allowCrossDomain([
    'Access-Control-Allow-Origin'        => 'http://localhost:8000',
    'Access-Control-Allow-Credentials'   => 'true'
]);

