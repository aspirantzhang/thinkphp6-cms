<?php

use think\facade\Route;

Route::group('menus', function () {
    Route::get('backend', 'backend');
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
})->prefix('menu/')->middleware(app\middleware\RouterValidate::class, 'Menu');
