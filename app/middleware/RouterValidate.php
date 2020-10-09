<?php

declare(strict_types=1);

namespace app\middleware;

class RouterValidate
{
    public function handle($request, \Closure $next, $name)
    {
        $appName = parse_name(app('http')->getName());
        $validateClassName = 'app\\' . $appName . '\\validate\\' . $name;
        $validateClass = new $validateClassName();
        $validateClass->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
