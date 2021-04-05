<?php

namespace tests\api;

use think\App;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        (new App())->http->run();
    }
    protected function tearDown(): void
    {
    }
}
