<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::post('refresh-token', 'refreshToken');
    Route::post('login', 'login');
    Route::get('add', 'add');

    Route::post(':id', 'update');
    Route::get(':id', 'view');

    Route::get('', 'index');
    Route::post('', 'store');
})->prefix('admin/');
