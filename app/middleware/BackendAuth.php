<?php

declare(strict_types=1);

namespace app\middleware;

use aspirantzhang\TP6Auth\Auth;

class BackendAuth
{
    protected $noNeedAuth = [
        'backend/index/login',
        // 'backend/admin/home',
        // 'backend/admin/add',
        // 'backend/admin/save',
        // 'backend/admin/read',
        // 'backend/admin/update',
        // 'backend/admin/delete',
        // 'backend/admin/batch_delete',
        'backend/auth_group/home',
        'backend/auth_group/add',
        'backend/auth_group/save',
        'backend/auth_group/read',
        'backend/auth_group/edit',
        'backend/auth_group/update',
        'backend/auth_group/delete',
        'backend/auth_group/tree',
        'backend/auth_group/batch_delete',
        'backend/auth_group/test',
        'backend/auth_rule/home',
        'backend/auth_rule/add',
        'backend/auth_rule/save',
        'backend/auth_rule/read',
        'backend/auth_rule/edit',
        'backend/auth_rule/update',
        'backend/auth_rule/delete',
        'backend/auth_rule/tree',
        'backend/auth_rule/batch_delete',
    ];

    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $controllerName = parse_name($request->controller());
        $actionName = parse_name($request->action());

        // echo $appName . '/' . $controllerName . '/' . $actionName;

        $fullPath = $appName . '/' . $controllerName . '/' . $actionName;

        if (in_array($fullPath, $this->noNeedAuth)) {
            return $next($request);
        } else {
            $auth = new Auth();

            if ($auth->check($fullPath, 1)) {
                return $next($request);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'No permission.',
                ];
                return json($data)->header([
                    'access-control-allow-origin' => 'http://localhost:8000',
                    'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
                    'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
                    'access-control-allow-credentials' => 'true',
                ]);
            }
        }
    }
}
