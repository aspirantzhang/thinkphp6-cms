<?php

use think\facade\Route;

Route::group('groups', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('', 'save');
    Route::delete('', 'delete');
    Route::post('restore', 'restore');
})->prefix('auth_group/')->middleware(app\middleware\RouterValidate::class, 'AuthGroup');
