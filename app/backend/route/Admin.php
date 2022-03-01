<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get('', 'home');
})->prefix('admin/')->middleware(app\middleware\RouterValidate::class, 'Admin');
