<?php

use think\facade\Route;

Route::group('models', function () {
    Route::get('design/:id', 'design');
    Route::put('design/:id', 'designUpdate');
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
})->prefix('model/')->middleware(app\middleware\RouterValidate::class, 'Model');
