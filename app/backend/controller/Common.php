<?php

declare(strict_types=1);

namespace app\backend\controller;

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

    /**
    * @OA\OpenApi(
    *     @OA\Info(
    *         version="1.0.0",
    *         title="Public API v2",
    *         description="Online API",
    *         @OA\Contact(
    *             name="Aspirant Zhang",
    *             url="https://www.aspirantzhang.com",
    *             email="admin@aspirantzhang.com",
    *         ),
    *     ),

    *     @OA\Server(
    *         description="Online API",
    *         url="http://www.test.com/",
    *     ),
    *     @OA\Server(
    *         description="Local API",
    *         url="http://localhost:8000/api/",
    *     ),
    *     @OA\Tag(
    *         name="admins",
    *         description="Operations about administrator",
    *     ),
    *     @OA\Tag(
    *         name="groups",
    *         description="Operations about groups of administrator",
    *     ),
    *     @OA\Tag(
    *         name="rules",
    *         description="Operations about rule",
    *     ),
    *     @OA\Tag(
    *         name="menus",
    *         description="Operations about menu",
    *     ),
    *     @OA\Tag(
    *         name="models",
    *         description="Operations about model",
    *     ),
    * ),
    *
    * @OA\Components(
    *     @OA\SecurityScheme(
    *         type="apiKey",
    *         securityScheme="ApiKeyAuth",
    *         name="X-API-KEY",
    *         in="query"
    *     ),
    * ),
    *
    */
    
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
