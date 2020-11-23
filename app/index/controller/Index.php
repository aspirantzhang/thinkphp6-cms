<?php

namespace app\index\controller;

use app\BaseController;
use think\facade\Config;

class Index extends BaseController
{
    public function home()
    {
        return redirect('/admin/api');
    }
    public function api()
    {
        $openapi = \OpenApi\scan(base_path());
        return response()->data($openapi->toYaml())->header(array_merge(Config::get('route.default_header'), [
            'Content-Type' => 'application/x-yaml',
        ]));
    }
}
