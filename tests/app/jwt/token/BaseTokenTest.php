<?php

declare(strict_types=1);

namespace tests\app\jwt\token;

use app\jwt\exception\TokenExpiredException;
use app\jwt\exception\TokenInvalidException;
use app\jwt\token\BaseToken;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Firebase\JWT\JWT as JWT_LIB;
use Mockery as m;

class JWTTest extends \tests\TestCase
{
    protected $class;

    public function setUp(): void
    {
        $mock = m::mock('overload:think\facade\Config');
        $mock->shouldReceive('get')->with('jwt.key')->once()->andReturn('fake_key');
        $mock->shouldReceive('get')->with('jwt.iss')->once()->andReturn('fake_iss');
        $mock->shouldReceive('get')->with('jwt.aud')->once()->andReturn('fake_aud');
        $mock->shouldReceive('get')->with('jwt.exp')->once()->andReturn(30);
        $mock->shouldReceive('get')->with('jwt.renew')->once()->andReturn(10000);
        $mock->shouldReceive('get')->with('jwt.alg')->once()->andReturn('HS256');
        CarbonImmutable::setTestNow(Carbon::parse(1600000000));
        $this->initClass();
    }

    private function initClass()
    {
        $this->class = new class() extends BaseToken {
            public function getToken()
            {
                $payload = $this->getClaims();

                return JWT_LIB::encode($payload, $this->secretKey, $this->algorism);
            }
        };
    }

    public function testGetAddClaim()
    {
        $refreshExpire = 1600009999;
        $result = $this->class->addClaim('exp', $refreshExpire)->addClaim('foo', 'bar')->getClaims();
        $this->assertEquals(1600009999, $result['exp']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function testGetAddClaims()
    {
        $added = ['foo1' => 'bar1', 'foo' => 8, 'exp' => 123];
        $result = $this->class->addClaims($added)->getClaims();
        $this->assertEquals('bar1', $result['foo1']);
        $this->assertEquals(8, $result['foo']);
        $this->assertEquals(123, $result['exp']);
    }

    public function testGetClaimsReturnDefaultClaims()
    {
        $result = $this->class->getClaims();

        $this->assertEquals('fake_iss', $result['iss']);
        $this->assertEquals('fake_aud', $result['aud']);
        $this->assertEquals('access_token', $result['grant_type']);
        $this->assertEquals($result['nbf'], $result['iat']);
        $this->assertEquals($result['iat'] + 30, $result['exp']);
    }

    public function testGetClaimForDefaultValue()
    {
        $result = $this->class->getClaim('exp');
        $this->assertEquals(1600000030, $result);
    }

    public function testGetClaimForExtraValue()
    {
        $jwt = $this->class->addClaim('foo', 'bar');
        $result = $jwt->getClaim('foo');

        $this->assertEquals('bar', $result);
    }

    public function testCheckTokenWithValidTokenString()
    {
        CarbonImmutable::setTestNow();
        $this->initClass();

        $token = $this->class->addClaim('foo', 'bar')->getToken();
        $result = $this->class->checkToken($token);
        $this->assertEquals('fake_iss', $result['iss']);
        $this->assertEquals('fake_aud', $result['aud']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function testCheckTokenWithExpiredTokenShouldThrowError()
    {
        $this->expectException(TokenExpiredException::class);
        $this->expectExceptionMessage('token expired');

        $token = $this->class->addClaim('foo', 'bar')->getToken();
        $this->class->checkToken($token);
    }

    public function testCheckTokenWithInvalidTokenShouldThrowError()
    {
        $this->expectException(TokenInvalidException::class);
        $this->expectExceptionMessage('token invalid');

        $this->class->checkToken('invalid');
    }
}
