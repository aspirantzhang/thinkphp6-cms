<?php

declare(strict_types=1);

namespace app\middleware;

use think\facade\Config;
use aspirantzhang\TP6Auth\Auth;
use think\facade\Session;

class BackendAuth
{
    protected $noNeedAuth = [
        'api/admin/login',
        'api/admin/logout',
        'api/admin/info',
        'api/menu/backend',
    ];

    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $controllerName = parse_name($request->controller());
        $actionName = parse_name($request->action());
        
        $fullPath = $appName . '/' . $controllerName . '/' . $actionName;

        if (in_array($fullPath, $this->noNeedAuth) || $request->param('X-API-KEY') == 'antd') {
            return $next($request);
        } else {
            $auth = new Auth();
            if (!Session::has('userId')) {
                $data = [
                    'success' => false,
                    'message' => 'Your session has expired, please log in again.',
                ];
                return json($data)->header(Config::get('route.default_header'));
            }
            
            if ($auth->check($fullPath, Session::get('userId'))) {
                return $next($request);
            } else {
                $data = [
                    'success' => false,
                    'message' => 'No permission.',
                ];
                return json($data)->header(Config::get('route.default_header'));
            }
        }
    }
}
