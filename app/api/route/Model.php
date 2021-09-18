<?php

use think\facade\Route;

Route::group('models', function () {
    Route::get('design/:id', 'design');
    Route::put('design/:id', 'designUpdate');
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id/i18n', 'i18n');
    Route::patch(':id/i18n', 'i18nUpdate');
    Route::get(':id/revisions/:revisionId', 'revisionRead');
    Route::get(':id/revisions', 'revision');
    Route::post(':id/revisions', 'revisionRestore');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
})->prefix('model/')->middleware(app\middleware\RouterValidate::class, 'Model');
