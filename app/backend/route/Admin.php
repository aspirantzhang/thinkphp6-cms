<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::post(':id', 'update');
    Route::get(':id', 'view');
    Route::get('add', 'add');
    Route::post('login', 'login');
    Route::get('', 'index');
    Route::post('', 'store');
    Route::post('/refresh-token', 'refreshToken');
})->prefix('admin/');
