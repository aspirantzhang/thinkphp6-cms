<?php

use think\facade\Route;

Route::group('groups', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::patch(':id', 'update');
    Route::post('', 'save');
    Route::delete('batch-delete', 'batchDelete');
    Route::delete(':id', 'delete');
})->prefix('auth_group/')->middleware(app\middleware\RouterValidate::class, 'AuthGroup');
