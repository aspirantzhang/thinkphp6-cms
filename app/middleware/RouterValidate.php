<?php

declare(strict_types=1);

namespace app\middleware;

use app\core\exception\SystemException;

class RouterValidate
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

        $validateClass = new class ($module) extends \think\Validate {
            public function __construct(private $module)
            {
                $this->setLang(app()->lang);
                $this->scene['index'] = ['admin_name'];

                $this->message['admin_name.length'] = 'octopus-length';
            }
        };

        (new $validateClass($module))->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
