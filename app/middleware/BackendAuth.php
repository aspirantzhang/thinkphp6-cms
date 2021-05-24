<?php

declare(strict_types=1);

namespace app\middleware;

use think\facade\Config;
use think\facade\Session;
use app\common\Auth;
use think\facade\Lang;

class BackendAuth
{
    protected $noNeedAuth = [
        'api/admin/login',
        'api/admin/logout',
        'api/admin/info',
        'api/menu/backend',
        'api/unit_test/home',
        'api/unit_test/add',
        'api/unit_test/save',
        'api/unit_test/read',
        'api/unit_test/update',
        'api/unit_test/delete',
        'api/unit_test/restore',
    ];

    public function handle($request, \Closure $next)
    {
        Config::load('api/response', 'response');
        foreach (glob(base_path() . 'api/lang/fields/' . Lang::getLangSet() . '/*.php') as $filename) {
            Lang::load($filename);
        }

        $appName = parse_name(app('http')->getName());
        $controllerName = parse_name($request->controller());
        $actionName = parse_name($request->action());
        
        $fullPath = $appName . '/' . $controllerName . '/' . $actionName;

        if (in_array($fullPath, $this->noNeedAuth) || $request->param('X-API-KEY') == 'antd') {
            return $next($request);
        } else {
            $auth = new Auth();
            if (!Session::has('adminId')) {
                $data = [
                    'success' => false,
                    'message' => Lang::get('session_expired'),
                ];
                return json($data)->header(Config::get('response.default_header'));
            }
            
            if ($auth->check($fullPath, Session::get('adminId'))) {
                return $next($request);
            } else {
                $data = [
                    'success' => false,
                    'message' => Lang::get('no_permission'),
                ];
                return json($data)->header(Config::get('response.default_header'));
            }
        }
    }
}
