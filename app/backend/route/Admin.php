<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get(':id', 'view');
    Route::get('', 'index');
    Route::post('/login', 'login');
    Route::post('', 'store');
    Route::post('/refresh-token', 'refreshToken');
})->prefix('admin/');
