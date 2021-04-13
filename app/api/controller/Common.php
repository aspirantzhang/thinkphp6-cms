<?php

declare(strict_types=1);

namespace app\api\controller;

use think\Response;
use think\facade\Config;
use think\facade\Db;
use app\common\controller\GlobalController;

class Common extends GlobalController
{
    protected $error = '';

    public function initialize()
    {
        Config::load('api/field', 'field');
        Config::load('api/model', 'model');
        Config::load('api/response', 'response');
        parent::initialize();
    }
    
    protected function json($data = [], $code = 200, $header = [], $options = [], ...$rest)
    {
        return Response::create($data, 'json', $code)->header(array_merge(Config::get('response.default_header') ?: [], $header))->options($options);
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
