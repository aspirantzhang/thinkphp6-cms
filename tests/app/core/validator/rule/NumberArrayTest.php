<?php

declare(strict_types=1);

use app\core\validator\rule\NumberArray;
use Mockery as m;

// support number string, number array or single number
class NumberArrayTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $validate = m::mock(think\Validate::class);
        $this->class = new NumberArray($validate);
    }

    public function testRuleIfValueIsInvalidShouldReturnFalse()
    {
        $this->assertFalse($this->class->rule('foo'));
        $this->assertFalse($this->class->rule('foo bar'));
        $this->assertFalse($this->class->rule('\n'));
        $this->assertFalse($this->class->rule('\t'));
        $this->assertFalse($this->class->rule('\r'));
        $this->assertFalse($this->class->rule(''));
        $this->assertFalse($this->class->rule(' '));
        $this->assertFalse($this->class->rule('π'));
        $this->assertFalse($this->class->rule(0.688));
        $this->assertFalse($this->class->rule([111, 'foo', false]));
    }

    public function testRuleIfValueIsNumberShouldReturnTrue()
    {
        $this->assertTrue($this->class->rule(111));
        $this->assertTrue($this->class->rule('666'));
        $this->assertTrue($this->class->rule(true));
        $this->assertTrue($this->class->rule(false));
    }

    public function testRuleIfValueIsNumberArrayShouldReturnTrue()
    {
        $this->assertTrue($this->class->rule([111, '666']));
        $this->assertTrue($this->class->rule([true, false]));
    }
}
