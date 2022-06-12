<?php

declare(strict_types=1);

namespace app\jwt;

class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind('jwt', Jwt::class);
    }
}
