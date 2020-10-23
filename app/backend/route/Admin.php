<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get('logout', 'logout');
    Route::get('info', 'info');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('login', 'login');
    Route::post('', 'save');
    Route::delete('', 'delete');
    Route::post('restore', 'restore');
})->prefix('admin/')->middleware(app\middleware\RouterValidate::class, 'Admin');
