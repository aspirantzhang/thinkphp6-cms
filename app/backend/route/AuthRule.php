<?php

use think\facade\Route;

Route::group('rules', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::patch(':id', 'update');
    Route::post('', 'save');
    Route::delete('batch-delete', 'batchDelete');
    Route::delete(':id', 'delete');
})->prefix('auth_rule/')->middleware(app\middleware\RouterValidate::class, 'AuthRule');
