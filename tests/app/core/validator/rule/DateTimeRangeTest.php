<?php

declare(strict_types=1);

use app\core\validator\rule\DateTimeRange;
use Mockery as m;

class DateTimeRangeTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $validate = m::mock(think\Validate::class);
        $this->class = new DateTimeRange($validate);
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
        $this->assertFalse($this->class->rule('张'));
    }

    public function testRuleIfValueIsInvalidDateShouldReturnFalse()
    {
        $this->assertFalse($this->class->rule('2022 01 05 24:00'));
        $this->assertFalse($this->class->rule('2022.01.05 1:00pm'));
        $this->assertFalse($this->class->rule('2022-01-05 01:00'));
        $this->assertFalse($this->class->rule('2022-02-28T16:00:20Z'));
        $this->assertFalse($this->class->rule('2022-01-05T16:00:20+0800'));
    }

    public function testRuleIfValueIsValidDateShouldReturnTrue()
    {
        $this->assertTrue($this->class->rule('2022-01-05T16:00:20+08:00'));
    }

    public function testRuleIfValueIsInvalidDateRangeShouldReturnFalse()
    {
        $this->assertFalse($this->class->rule('2022-02-28T16:00:20Z,2022-02-28T16:00:20Z'));
        $this->assertFalse($this->class->rule('2022-01-05T16:00:20+08:00,2022-02-28T16:00:20Z'));
    }

    public function testRuleIfValueIsValidDateRangeShouldReturnTrue()
    {
        $this->assertTrue($this->class->rule('2022-01-05T16:00:20+08:00,2022-12-31T16:59:20+01:00'));
    }
}
