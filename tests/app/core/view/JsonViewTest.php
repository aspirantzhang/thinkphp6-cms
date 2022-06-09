<?php

declare(strict_types=1);

use app\core\view\JsonView;
use Mockery as m;

class JsonViewTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $this->mock = m::mock('overload:think\facade\Config');
    }

    public function testInitHeaderWithDefaultValue()
    {
        $this->mock->shouldReceive('get')->with('response.default_header')->once()->andReturn([]);
        $this->class = new JsonView([]);
        $header = $this->getPropertyValue('header');
        $this->assertEqualsCanonicalizing([
            'access-control-allow-origin' => '*',
            'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
            'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'access-control-allow-credentials' => 'true',
        ], $header);
    }

    public function testInitHeaderWithConfigCustomValue()
    {
        $this->mock->shouldReceive('get')->with('response.default_header')->once()->andReturn([
            'access-control-allow-origin' => 'localhost',
            'foo' => 'bar',
        ]);
        $this->class = new JsonView([]);
        $header = $this->getPropertyValue('header');
        $this->assertEqualsCanonicalizing([
            'access-control-allow-origin' => 'localhost',
            'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
            'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'access-control-allow-credentials' => 'true',
            'foo' => 'bar',
        ], $header);
    }

    public function testInitHeaderWithCustomDataValue()
    {
        $this->mock->shouldReceive('get')->with('response.default_header')->once()->andReturn([]);
        $data = [
            'header' => [
                'access-control-allow-origin' => 'localhost',
                'bar' => 'baz',
            ],
        ];
        $this->class = new JsonView($data);
        $header = $this->getPropertyValue('header');
        $this->assertEqualsCanonicalizing([
            'access-control-allow-origin' => 'localhost',
            'access-control-allow-methods' => 'GET, POST, PATCH, PUT, DELETE, OPTIONS',
            'access-control-allow-headers' => 'Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'access-control-allow-credentials' => 'true',
            'bar' => 'baz',
        ], $header);
    }

    public function testInitBodyWithDefaultValue()
    {
        $this->mock->shouldReceive('get')->with('response.default_header')->once()->andReturn([]);
        $this->class = new JsonView([]);
        $body = $this->getPropertyValue('body');
        $this->assertEqualsCanonicalizing([
            'success' => false,
            'message' => 'unknown error',
            'data' => [],
        ], $body);
    }

    public function testInitBodyWithCustomDataValue()
    {
        $this->mock->shouldReceive('get')->with('response.default_header')->once()->andReturn([]);
        $data = [
            'success' => true,
            'message' => 'ok',
            'data' => [
                'foo' => 'bar',
            ],
            'header' => [
                'headerFoo' => 'headerBar',
            ],
            'code' => 999,
        ];
        $this->class = new JsonView($data);
        $body = $this->getPropertyValue('body');
        $this->assertEqualsCanonicalizing([
            'success' => true,
            'message' => 'ok',
            'data' => [
                'foo' => 'bar',
            ],
        ], $body);
    }
}
