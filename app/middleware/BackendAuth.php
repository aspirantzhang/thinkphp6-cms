<?php

<<<<<<< HEAD
declare(strict_types=1);

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
namespace app\middleware;

use aspirantzhang\TP6Auth\Auth;

<<<<<<< HEAD
class BackendAuth
{
=======

class BackendAuth
{

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    protected $noNeedAuth = [
        'backend/index/login',
        'backend/admin/index',
        'backend/admin/create',
        'backend/admin/save',
        'backend/admin/read',
        'backend/admin/edit',
        'backend/admin/update',
        'backend/admin/delete',
        'backend/admin/groups',
        'backend/auth_group/index',
        'backend/auth_group/create',
        'backend/auth_group/save',
        'backend/auth_group/read',
        'backend/auth_group/edit',
        'backend/auth_group/update',
        'backend/auth_group/delete',
        'backend/auth_rule/index',
        'backend/auth_rule/create',
        'backend/auth_rule/save',
        'backend/auth_rule/read',
        'backend/auth_rule/edit',
        'backend/auth_rule/update',
        'backend/auth_rule/delete',
        'backend/auth_rule/menus',
    ];

    public function handle($request, \Closure $next)
    {
<<<<<<< HEAD
=======

>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        $appName = parse_name(app('http')->getName());
        $controllerName = parse_name($request->controller());
        $actionName = parse_name($request->action());
        $fullPath = $appName.'/'.$controllerName.'/'.$actionName;

        if (in_array($fullPath, $this->noNeedAuth)) {
            return $next($request);
        } else {
<<<<<<< HEAD
            $auth = new Auth();
=======

            $auth = new Auth;
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5

            if ($auth->check($fullPath, 1)) {
                return $next($request);
            } else {
<<<<<<< HEAD
                $data = [
                    'status' => 'error',
                    'msg' => 'No permission.',
                ];

=======

                $data = [
                    'status'    =>  'error',
                    'msg'       =>  'No permission.',
                ];
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
                return json($data)->header([
                    'access-control-allow-origin' => 'http://localhost:8000',
                    'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE',
                    'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
                    'access-control-allow-credentials' => 'true',
                ]);
            }
        }
<<<<<<< HEAD
=======


>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
    }
}
