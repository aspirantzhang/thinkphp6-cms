<?php

declare(strict_types=1);

namespace app\jwt\middleware;

use app\core\view\JsonView;
use app\jwt\exception\TokenExpiredException;
use app\jwt\exception\TokenInvalidException;

class Jwt
{
    public function handle($request, \Closure $next)
    {
        $ignoreList = ['/backend/admins/login', '/backend/admins/refresh-token'];
        if (in_array($request->url() ?? '', $ignoreList)) {
            return $next($request);
        }

        try {
            app('jwt')->checkAccessToken($request);
        } catch (TokenExpiredException) {
            return (new JsonView(error(message: 'token expired', code: 401)))->output();
        } catch (TokenInvalidException) {
            return (new JsonView(error(message: 'invalid token', code: 401)))->output();
        }

        return $next($request);
    }
}
