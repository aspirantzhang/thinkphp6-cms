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
        $this->expectExceptionMessage('no fields founded in module: unit-test-table');

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

    public function testParseOperationWithValidArray()
    {
        $modelPosition = [
            [
                'name' => 'button1',
                'position' => 'list.tableToolbar',
            ],
            [
                'name' => 'button2',
                'position' => 'list.batchToolbar',
            ],
            [
                'name' => 'button3',
                'position' => 'other',
            ],
        ];
        $model = m::mock('app\core\model\Model');
        $model->shouldReceive('getModule')
            ->once()
            ->with('operation')
            ->andReturn($modelPosition);
        $this->class = new ListLayout($model);
        $this->getMethodInvoke('parseOperation');

        $this->assertEqualsCanonicalizing([[
            'name' => 'button1',
            'position' => 'list.tableToolbar',
        ]], $this->getPropertyValue('tableToolbar'));
        $this->assertEqualsCanonicalizing([[
            'name' => 'button2',
            'position' => 'list.batchToolbar',
        ]], $this->getPropertyValue('batchToolbar'));
    }

    public function testParseOperationWithEmptyArray()
    {
        $this->expectException(SystemException::class);
        $this->expectExceptionMessage('no operations founded in module: unit-test-table');

        $modelPosition = [];
        $model = m::mock('app\core\model\Model');
        $model->shouldReceive('getModule')
            ->once()
            ->with('operation')
            ->andReturn($modelPosition);
        $model->shouldReceive('getTableName')
            ->once()
            ->andReturn('unit-test-table');
        $this->class = new ListLayout($model);
        $this->getMethodInvoke('parseOperation');
    }

    public function testJsonSerializeInterface()
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
        $modelPosition = [
            [
                'name' => 'button1',
                'position' => 'list.tableToolbar',
            ],
            [
                'name' => 'button2',
                'position' => 'list.batchToolbar',
            ],
            [
                'name' => 'button3',
                'position' => 'other',
            ],
        ];
        $model = m::mock('app\core\model\Model');
        $model->shouldReceive('getModule')
            ->once()
            ->with('field')
            ->andReturn($modelField);
        $model->shouldReceive('getModule')
            ->once()
            ->with('operation')
            ->andReturn($modelPosition);
        $this->class = new ListLayout($model);
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
        $expect = [
            'page' => [],
            'layout' => [
                'tableColumn' => [
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
                ],
                'tableToolBar' => [
                    [
                        'name' => 'button1',
                        'position' => 'list.tableToolbar',
                    ],
                ],
                'batchToolBar' => [
                    [
                        'name' => 'button2',
                        'position' => 'list.batchToolbar',
                    ],
                ],
            ],
            'dataSource' => [
                ['admin_name' => 'zhang1'],
                ['admin_name' => 'zhang2'],
            ],
            'meta' => [
                'total' => 2,
                'per_page' => 10,
                'page' => 1,
            ],
        ];
        $this->assertEquals(json_encode($expect), json_encode($this->class->withData($data)));
    }
}
