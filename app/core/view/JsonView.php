<?php

declare(strict_types=1);

namespace app\core\view;

use think\facade\Config;
use think\Response;

class JsonView
{
    public function __construct(protected mixed $data, protected int $code = 200)
    {
    }

    public function output()
    {
        return Response::create($this->data, 'json', $this->code)->header(Config::get('response.default_header'));
    }
}
