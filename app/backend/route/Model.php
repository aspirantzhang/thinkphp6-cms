<?php

use think\facade\Route;

Route::group('models', function () {
    Route::post('', 'save');
})->prefix('model/')->middleware(app\middleware\RouterValidate::class, 'Model');
