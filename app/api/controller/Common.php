<?php

declare(strict_types=1);

namespace app\api\controller;

use think\Response;
use think\facade\Config;
use think\facade\Db;
use app\common\controller\GlobalController;

class Common extends GlobalController
{
    public function initialize()
    {
        parent::initialize();
    }
    
    protected function json($data = [], $code = 200, $header = [], $options = [])
    {
        return Response::create($data, 'json', $code)->header(array_merge(Config::get('route.default_header'), $header))->options($options);
    }

    protected function success(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => true, 'message' => $message, 'data' => $data];
        return $this->json($httpBody, 200, $header);
    }
    
    protected function error(string $message = '', array $data = [], array $header = [])
    {
        $httpBody = ['success' => false, 'message' => $message, 'data' => $data];
        return $this->json($httpBody, 200, $header);
    }

    protected function existsTable($tableName)
    {
        try {
            Db::query("select 1 from `$tableName` LIMIT 1");
        } catch (\Exception $e) {
            $this->error = "Table not found.";
            return false;
        }
        return true;
    }
}
