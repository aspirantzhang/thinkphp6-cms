<?php

declare(strict_types=1);

namespace tests\app\core\Layout;

use app\core\domain\Layout\ListLayout;
use app\core\exception\SystemException;
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

    public function testParseDataSourceWithValidArray()
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

    public function testParseDataSourceWithEmptyArray()
    {
        $data = [];
        $this->class->withData($data);
        $this->getMethodInvoke('parseDataSource');
        $this->assertNull($this->getPropertyValue('dataSource'));
        $this->assertNull($this->getPropertyValue('meta'));
    }

    public function testParseTableColumnWithValidArray()
    {
        $modelField = [
            [
                'name' => 'admin_name',
                'type' => 'input',
                'unique' => true,
                'filter' => true,
                'translate' => false,
                'position' => 'tab.main',
                'order' => 1,
            ],
            [
                'name' => 'comment',
                'type' => 'textarea',
                'unique' => false,
                'filter' => true,
                'translate' => true,
                'order' => 99,
                'position' => 'sidebar.main',
                'hideInColumn' => true,
            ],
        ];
        $model = m::mock('app\core\model\Model');
        $model->shouldReceive('getModule')
            ->once()
            ->with('field')
            ->andReturn($modelField);
        $this->class = new ListLayout($model);
        $this->getMethodInvoke('parseTableColumn');
        $expect = [
            [
                'name' => 'admin_name',
                'type' => 'input',
                'translate' => false,
                'order' => 1,
            ],
            [
                'name' => 'comment',
                'type' => 'textarea',
                'translate' => true,
                'order' => 99,
            ],
        ];
        $this->assertEqualsCanonicalizing($expect, $this->getPropertyValue('tableColumn'));
    }

    public function testParseTableColumnWithEmptyModelFieldShouldThrowException()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionMessage('unit-test-table');

        $modelField = [];
        $model = m::mock('app\core\model\Model');
        $model->shouldReceive('getModule')
            ->once()
            ->with('field')
            ->andReturn($modelField);
        $model->shouldReceive('getTableName')
            ->once()
            ->andReturn('unit-test-table');
        $this->class = new ListLayout($model);
        $this->getMethodInvoke('parseTableColumn');
    }
}
