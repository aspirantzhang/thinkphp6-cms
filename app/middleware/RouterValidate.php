<?php

declare(strict_types=1);

namespace app\middleware;

class RouterValidate
{
    public function handle($request, \Closure $next, $name)
    {
        $v = new $name();
        $v->failException(true)->scene(parse_name($request->action()))->check($request->param());

        return $next($request);
    }
}
