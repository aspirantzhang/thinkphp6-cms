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
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
    Route::patch(':id', 'i18nUpdate');
})->prefix('admin/')->middleware(app\middleware\RouterValidate::class, 'Admin');
