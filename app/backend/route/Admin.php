<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get('', 'index');
    Route::post('/login', 'login');
})->prefix('admin/');
