<?php

use think\facade\Route;

Route::group('admins', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get('trash', 'trash');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::patch(':id', 'update');
    Route::post('', 'save');
    Route::delete('batch-delete', 'batchDelete');
    Route::delete(':id', 'delete');
})->prefix('admin/')->middleware(app\middleware\RouterValidate::class, 'Admin');
