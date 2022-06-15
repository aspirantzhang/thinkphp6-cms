<?php

declare(strict_types=1);

namespace app\jwt\middleware;

use app\jwt\exception\TokenExpiredException;
use app\jwt\exception\TokenInvalidException;
use think\Response;

class Jwt
{
    public function handle($request, \Closure $next)
    {
        $ignoreList = ['/backend/admins/login', '/backend/admins/refresh-token'];
        if (in_array($request->url() ?? '', $ignoreList)) {
            return $next($request);
        }

        try {
            $payload = app('jwt')->checkAccessToken($request);
        } catch (TokenExpiredException) {
            return Response::create(error(message: 'token expired', code: 401), 'json', 401);
        } catch (TokenInvalidException) {
            return Response::create(error(message: 'invalid token', code: 401), 'json', 401);
        }

        return $next($request);
    }
}
