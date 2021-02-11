<?php

use think\facade\Route;

Route::group('groups', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
})->prefix('auth_group/')->middleware(app\middleware\RouterValidate::class, 'AuthGroup');
