<?php

declare(strict_types=1);

namespace app\jwt;

use app\jwt\token\AccessToken;
use app\jwt\token\RefreshToken;
use think\exception\ValidateException;
use think\facade\Db;

class Jwt
{
    public function getToken($payload = [])
    {
        $accessToken = (new AccessToken())->addClaims($payload)->getToken();
        $refresh = (new RefreshToken())->addClaims($payload);
        $refreshToken = $refresh->getToken();

        $jti = $refresh->getJti();
        $uid = $refresh->getUid();
        $ua = app('request')->header('user-agent');
        $record = [
            'uid' => $uid,
            'jti' => $jti,
            'ua' => $ua,
            'create_time' => date('Y-m-d H:i:s'),
            'status' => 1,
        ];

        $uidExists = Db::table('jwt_log')->where('uid', $uid)->find();
        if ($uidExists) {
            Db::name('jwt_log')->where('uid', $uid)->update($record);
        } else {
            Db::name('jwt_log')->insert($record);
        }

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }

    public function checkAccessToken($request)
    {
        $accessToken = $this->getRequestAccessToken($request);

        return (new AccessToken())->checkToken($accessToken);
    }

    public function getRequestAccessToken($request)
    {
        $bearer = $request->header('authorization');

        if (!str_starts_with($bearer ?? '', 'Bearer')) {
            throw new ValidateException('no permission');
        }

        return trim(strtr($bearer, ['Bearer' => '']));
    }

    public function getRequestRefreshToken($request)
    {
        $token = $request->cookie('refreshToken');
        if (empty($token)) {
            throw new ValidateException('no permission');
        }

        return trim($token);
    }

    public function checkRefreshToken($request)
    {
        $refreshToken = $this->getRequestRefreshToken($request);

        return (new RefreshToken())->checkToken($refreshToken);
    }

    public function refreshToken($request)
    {
        $payload = $this->checkRefreshToken($request);

        unset($payload['grant_type'], $payload['iat'], $payload['nbf'], $payload['exp'], $payload['jti']);

        $newAccessToken = (new AccessToken())->addClaims($payload)->getToken();

        return $newAccessToken;
    }
}
