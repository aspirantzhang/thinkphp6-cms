<?php

use think\facade\Route;

Route::group('', function () {
    Route::get('/', 'home');
    Route::get('api', 'api');
})->prefix('index/');
