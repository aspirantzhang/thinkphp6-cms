<?php

declare(strict_types=1);

namespace app\middleware;

use app\core\exception\SystemException;

class RouterValidate
{
    public function handle($request, \Closure $next, $name)
    {
        $appName = parse_name(app('http')->getName());
        $validateClass = 'app\\' . $appName . '\\validate\\' . $name;

        if (!class_exists($validateClass)) {
            throw new SystemException('invalid validate class: ' . $validateClass);
        }

        (new $validateClass())->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
