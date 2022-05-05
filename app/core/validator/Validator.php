<?php

declare(strict_types=1);

namespace app\core\validator;

use app\core\exception\SystemException;

class Validator
{
    public function handle($request, \Closure $next)
    {
        $appName = parse_name(app('http')->getName());
        $moduleName = $request->controller();
        $moduleClass = 'app\\' . $appName . '\\facade\\' . $moduleName;
        if (!class_exists($moduleClass)) {
            throw new SystemException('invalid module class: ' . $moduleClass);
        }
        $module = new $moduleClass();

        $validateClass = new class() extends \think\Validate {
            public function __construct()
            {
                $this->setLang(app()->lang);

                $this->rule['admin_name'] = 'length:6-32';

                $this->scene['index'] = ['admin_name'];

                $this->message['admin_name.length'] = 'octopus-length';
            }
        };

        (new $validateClass($module))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
