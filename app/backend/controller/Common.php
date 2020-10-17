<?php

declare(strict_types=1);

namespace app\backend\controller;

use think\Response;
use think\facade\Config;
use app\common\controller\GlobalController;

class Common extends GlobalController
{
    public function initialize()
    {
        parent::initialize();
    }
    
    public function json($data = [], $code = 200, $header = [], $options = [])
    {
        return Response::create($data, 'json', $code)->header(array_merge(Config::get('route.default_header'), $header))->options($options);
    }

    public function success(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        return $this->json($httpBody, 200, $header);
    }
    
    public function error(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        return $this->json($httpBody, 200, $header);
    }
}
