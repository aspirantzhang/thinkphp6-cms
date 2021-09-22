<?php

use think\facade\Route;

Route::group('groups', function () {
    Route::get('', 'home');
    Route::get('add', 'add');
    Route::get(':id/i18n', 'i18nRead');
    Route::patch(':id/i18n', 'i18nUpdate');
    Route::get(':id/revisions/:revisionId', 'revisionRead');
    Route::get(':id/revisions', 'revisionHome');
    Route::post(':id/revisions', 'revisionRestore');
    Route::post('delete', 'delete');
    Route::post('', 'save');
    Route::post('restore', 'restore');
    Route::get(':id', 'read');
    Route::put(':id', 'update');
})->prefix('auth_group/')->middleware(app\middleware\RouterValidate::class, 'AuthGroup');
