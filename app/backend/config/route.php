<?php

return [
    'middleware' => [
        app\core\validator\Validator::class,
        app\jwt\middleware\Jwt::class,
    ],
];
