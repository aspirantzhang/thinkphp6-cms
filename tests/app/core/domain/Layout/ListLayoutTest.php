<?php

declare(strict_types=1);

namespace tests\app\core\Layout;

use app\core\domain\Layout\ListLayout;
use Mockery as m;
use ReflectionClass;

class ListLayoutTest extends \tests\TestCase
{
    protected function setUp(): void
    {
        $model = m::mock('app\core\model\Model');
        $this->class = new ListLayout($model);
        $this->reflector = new ReflectionClass(ListLayout::class);
    }

    public function testParseDataSource()
    {
        $data = [
            'dataSource' => [
                ['admin_name' => 'zhang1'],
                ['admin_name' => 'zhang2'],
            ],
            'pagination' => [
                'total' => 2,
                'per_page' => 10,
                'page' => 1,
            ],
        ];
        $this->class->withData($data);
        $this->getMethodInvoke('parseDataSource');
        $this->assertEqualsCanonicalizing($data['dataSource'], $this->getPropertyValue('dataSource'));
        $this->assertEqualsCanonicalizing($data['pagination'], $this->getPropertyValue('meta'));
    }
}
