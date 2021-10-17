<?php

use think\facade\Route;

Route::group('', function () {
    Route::get('admins', 'admins');
})->prefix('index/');

Route::miss('page404');
