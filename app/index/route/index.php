<?php

use think\facade\Route;

Route::group('', function () {
    Route::get('admins', 'admins');
    Route::get('test', 'test');
})->prefix('index/');

Route::miss('page404');
