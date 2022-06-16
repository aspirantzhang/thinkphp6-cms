<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get('', 'index');
    Route::post('/login', 'login');
    Route::post('/refresh-token', 'refreshToken');
})->prefix('admin/');
