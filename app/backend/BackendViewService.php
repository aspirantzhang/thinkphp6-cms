<?php

declare(strict_types=1);

namespace app\backend;

use app\backend\view\JsonView;

class BackendViewService extends \think\Service
{
    public function register()
    {
        $this->app->bind('jsonView', JsonView::class);
    }
}
