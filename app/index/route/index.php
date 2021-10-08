<?php

use think\facade\Route;

Route::group('', function () {
    Route::get('test', 'test');
})->prefix('index/');

Route::miss('page404');
