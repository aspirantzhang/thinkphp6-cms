<?php

declare(strict_types=1);

use app\core\validator\rule\ParentId;
use Mockery as m;
use think\facade\Request;

// support number string, number array or single number
class ParentIdTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $validate = m::mock(think\Validate::class);
        $this->class = new ParentId($validate);
    }

    public function testRuleIfValueIsRecordId()
    {
        $request = m::mock('alias:' . Request::class);
        $request->shouldReceive('param')
            ->with('id')
            ->times(2)
            ->andReturn(3);
        $this->assertFalse($this->class->rule(3));
        $this->assertFalse($this->class->rule('3'));
    }

    public function testRuleIfValueIsValidShouldReturnTrue()
    {
        $request = m::mock('alias:' . Request::class);
        $request->shouldReceive('param')
            ->with('id')
            ->times(10)
            ->andReturn(3);
        $this->assertTrue($this->class->rule('foo'));
        $this->assertTrue($this->class->rule('foo bar'));
        $this->assertTrue($this->class->rule('\n'));
        $this->assertTrue($this->class->rule('\t'));
        $this->assertTrue($this->class->rule('\r'));
        $this->assertTrue($this->class->rule(''));
        $this->assertTrue($this->class->rule(' '));
        $this->assertTrue($this->class->rule('张'));
        $this->assertTrue($this->class->rule(0.688));
        $this->assertTrue($this->class->rule([111, 'foo', false]));
    }
}
