<?php

declare(strict_types=1);

namespace tests\app\core\traits;

use app\core\traits\ModuleInfo;

class ModuleInfoTest extends \tests\TestCase
{
    public function testGetTableName()
    {
        $trait = new class() {
            public string $name;
            use ModuleInfo;
        };
        $trait->name = 'UnitTest';
        $this->assertEquals('unit_test', $trait->getTableName());
        $trait->name = 'unit test';
        $this->assertEquals('unit_test', $trait->getTableName());
        $trait->name = 'unitTest';
        $this->assertEquals('unit_test', $trait->getTableName());
    }
}
