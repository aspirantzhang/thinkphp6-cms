<?php

declare(strict_types=1);

namespace tests\app\jwt;

use app\jwt\JWT;
use Mockery as m;

class JWTTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $mock = m::mock('overload:think\facade\Config');
        $mock->shouldReceive('get')->with('jwt.key')->once()->andReturn('fake_key');
        $mock->shouldReceive('get')->with('jwt.iss')->once()->andReturn('fake_iss');
        $mock->shouldReceive('get')->with('jwt.aud')->once()->andReturn('fake_aud');
        $mock->shouldReceive('get')->with('jwt.exp')->once()->andReturn('30');
        $mock->shouldReceive('get')->with('jwt.renew')->once()->andReturn('10000');
    }

    public function testGetAddClaim()
    {
        $refreshExpire = 9999;
        $result = (new JWT())->addClaim('exp', $refreshExpire)->addClaim('foo', 'bar')->getClaims();
        $this->assertEquals(9999, $result['exp']);
        $this->assertEquals('bar', $result['foo']);
    }

    public function testGetClaimsReturnDefaultClaims()
    {
        $result = (new JWT())->getClaims();

        $this->assertEquals('fake_iss', $result['iss']);
        $this->assertEquals('fake_aud', $result['aud']);
        $this->assertEquals($result['nbf'], $result['iat']);
        $this->assertEquals($result['iat'] + 30, $result['exp']);
    }

    public function testGetClaimForDefaultValue()
    {
        $result = (new JWT())->getClaim('exp');
        $this->assertEquals(time() + 30, $result);
    }

    public function testGetClaimForExtraValue()
    {
        $jwt = (new JWT())->addClaim('foo', 'bar');
        $result = $jwt->getClaim('foo');

        $this->assertEquals('bar', $result);
    }
}
