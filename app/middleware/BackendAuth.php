<?php

declare(strict_types=1);

namespace app\middleware;

use think\facade\Config;
use aspirantzhang\TP6Auth\Auth;

class BackendAuth
{
    protected $noNeedAuth = [
        'backend/menu/backend',
        'backend/admin/login',
        'backend/admin/info',
    ];

    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $controllerName = parse_name($request->controller());
        $actionName = parse_name($request->action());
        
        $fullPath = $appName . '/' . $controllerName . '/' . $actionName;

        if (in_array($fullPath, $this->noNeedAuth)) {
            return $next($request);
        } else {
            $auth = new Auth();

            if ($auth->check($fullPath, 199)) {
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
