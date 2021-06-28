<?php

use think\facade\Route;

Route::group('rules', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id/i18n', 'i18n');
    Route::patch(':id/i18n', 'i18nUpdate');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
})->prefix('auth_rule/')->middleware(app\middleware\RouterValidate::class, 'AuthRule');
