<?php

declare(strict_types=1);

namespace app\jwt;

use app\jwt\exception\TokenInvalidException;
use app\jwt\token\AccessToken;
use app\jwt\token\RefreshToken;
use think\exception\ValidateException;
use think\facade\Db;
use think\Request;

class Jwt
{
    private bool $stateful = false;

    public function setStateful(bool $value)
    {
        $this->stateful = $value;

        return $this;
    }

    public function getToken($claims = [])
    {
        $accessToken = (new AccessToken())->addClaims($claims)->getToken();
        $refresh = (new RefreshToken())->addClaims($claims);
        $refreshToken = $refresh->getToken();

        $this->updateRefreshTokenInDb($refresh);

        return [
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
        ];
    }

    private function updateRefreshTokenInDb(RefreshToken $refresh)
    {
        if ($this->stateful === false) {
            return;
        }

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
    }

    public function checkAccessToken(Request $request)
    {
        $accessToken = $this->getRequestAccessToken($request);

        return (new AccessToken())->checkToken($accessToken);
    }

    public function getRequestAccessToken(Request $request)
    {
        $bearer = $request->header('authorization');

        if (!str_starts_with($bearer, 'Bearer')) {
            throw new ValidateException('no permission');
        }

        return trim(strtr($bearer, ['Bearer' => '']));
    }

    public function getRequestRefreshToken(Request $request)
    {
        $token = $request->cookie('refreshToken');
        if (empty($token)) {
            throw new ValidateException('no permission');
        }

        return trim($token);
    }

    public function checkRefreshToken(Request $request)
    {
        $refreshToken = $this->getRequestRefreshToken($request);

        return (new RefreshToken())->checkToken($refreshToken);
    }

    public function refreshToken(Request $request)
    {
        $claims = $this->checkRefreshToken($request);

        $this->checkRefreshTokenInDb($request, $claims);

        unset($claims['grant_type'], $claims['iat'], $claims['nbf'], $claims['exp'], $claims['jti']);

        $newAccessToken = (new AccessToken())->addClaims($claims)->getToken();

        return $newAccessToken;
    }

    private function checkRefreshTokenInDb(Request $request, array $claims)
    {
        if ($this->stateful === false) {
            return;
        }

        $ua = $request->header('user-agent');

        $result = Db::name('jwt_log')->where('jti', $claims['jti'])->where('ua', $ua)->find();
        if (!$result) {
            throw new TokenInvalidException('invalid refresh token');
        }
    }
}
