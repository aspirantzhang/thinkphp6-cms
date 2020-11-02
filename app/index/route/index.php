<?php

use think\facade\Route;

Route::group('', function () {
    Route::get('api', 'api');
})->prefix('index/');
