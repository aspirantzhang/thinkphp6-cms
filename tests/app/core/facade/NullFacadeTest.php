<?php

declare(strict_types=1);

namespace tests\app\core\Lists;

use app\core\exception\SystemException;
use app\core\facade\NullFacade;
use Mockery as m;

class NullFacadeTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $mock = m::mock(NullFacade::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $mock->shouldReceive('initModel')->andReturn(null);
        $this->class = $mock;
    }

    public function testIsNullMethod()
    {
        $this->assertTrue($this->class->isNull());
    }

    public function testAnyMethodCallShouldThrowAnException()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionMessage('invalid facade');
        $this->class->foo();
    }

    public function testAnyPropertyGetShouldThrowAnException()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionMessage('invalid facade');
        $this->class->foo;
    }
}
