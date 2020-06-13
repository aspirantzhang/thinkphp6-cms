<?php

use think\facade\Route;

Route::group(function () {
    Route::post('login', 'index/login')->validate(\app\backend\validate\Admin::class, 'login');

    Route::group('admins', function () {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::get(':id', 'read');
        Route::put(':id', 'update');
        Route::patch(':id', 'update');
        Route::get(':id/groups', 'groups');
        Route::get('edit/:id', 'edit');
        Route::post('save', 'save');
        Route::post('delete/:id', 'delete');
    })->prefix('admin/')->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\Admin::class);

    Route::group('groups', function () {
        Route::get('', 'index');
        Route::get('create', 'create');
        Route::get('read/:id', 'read');
        Route::get('edit/:id', 'edit');
        Route::get('tree', 'tree');
        Route::post('save', 'save');
        Route::post('update/:id', 'update');
        Route::post('delete/:id', 'delete');
    })->prefix('auth_group/')->middleware(\app\middleware\RouterValidate::class, \app\backend\validate\AuthGroup::class);

    Route::group('rules', function () {
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
    'access-control-allow-origin' => 'http://localhost:8000',
    'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
    'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
    'access-control-allow-credentials' => 'true',
]);
