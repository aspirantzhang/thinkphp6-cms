<?php

<<<<<<< HEAD
declare(strict_types=1);

=======
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
namespace app\middleware;

class RouterValidate
{
    public function handle($request, \Closure $next, $name)
    {
<<<<<<< HEAD
        $v = new $name();
        $v->failException(true)->scene(parse_name($request->action()))->check($request->param());

=======
        $v = new $name;
        $v->failException(true)->scene(parse_name($request->action()))->check($request->param());
>>>>>>> b6480087703f4be4c8d74cd5b9cb0dd4101e42d5
        return $next($request);
    }
}
