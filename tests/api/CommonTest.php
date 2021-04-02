<?php

declare(strict_types=1);

namespace tests\api;

require_once('./app/api/common.php');


class CommonTest extends TestCase
{
    /**
    * @covers ::validateDateTime
    */
    public function testInvalidDatetimeShouldReturnFalse()
    {
        $this->assertFalse(validateDateTime(''));
        $this->assertFalse(validateDateTime(0));
        $this->assertFalse(validateDateTime([]));
        $this->assertFalse(validateDateTime(null));
        $this->assertFalse(validateDateTime("\t"));
        $this->assertFalse(validateDateTime("\n"));
        $this->assertFalse(validateDateTime("\r"));
        $this->assertFalse(validateDateTime(' '));
        $this->assertFalse(validateDateTime(true));
        $this->assertFalse(validateDateTime(false));
    }
    /**
    * @covers ::validateDateTime
    */
    public function testValidDatetimeShouldReturnTrue()
    {
        $this->assertTrue(validateDateTime('2020-04-02 11:59:59'));
    }
}
