<?php

declare(strict_types=1);

namespace tests\app\core\Layout;

use app\core\domain\Layout\ListLayout;
use Mockery as m;

class ListLayoutTest extends \tests\TestCase
{
    protected function setUp(): void
    {
        $model = m::mock('app\core\BaseModel');
        $this->class = new ListLayout($model);
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
        $this->class->setData($data);
        $this->getReflectMethod('parseDataSource');
        $this->assertEqualsCanonicalizing($data['dataSource'], $this->getPropertyValue('dataSource'));
        $this->assertEqualsCanonicalizing($data['pagination'], $this->getPropertyValue('meta'));
    }

    public function testParseDataSourceWithEmptyArray()
    {
        $data = [];
        $this->class->setData($data);
        $this->getReflectMethod('parseDataSource');
        $this->assertEmpty($this->getPropertyValue('dataSource'));
        $this->assertEmpty($this->getPropertyValue('meta'));
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
                'foo' => 'bar',
                'bar' => 'baz',
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
                'foo' => 'bar',
                'bar' => 'baz',
            ],
        ];

        $model = m::mock('app\core\BaseModel');
        $model->shouldReceive('getModuleField')
            ->once()
            ->andReturn($modelField);
        $this->class = (new ListLayout($model))
            ->setColumnKey(['name', 'type', 'order', 'position', 'hideInColumn', 'foo']);
        $this->getReflectMethod('parseTableColumn');

        $expect = [
            [
                'name' => 'admin_name',
                'type' => 'input',
                'order' => 1,
                'position' => 'tab.main',
                'hideInColumn' => null,
                'foo' => 'bar',
            ],
            [
                'name' => 'comment',
                'type' => 'textarea',
                'order' => 99,
                'position' => 'sidebar.main',
                'hideInColumn' => true,
                'foo' => 'bar',
            ],
        ];
        $this->assertEqualsCanonicalizing($expect, $this->getPropertyValue('tableColumn'));
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
        $model = m::mock('app\core\BaseModel');
        $model->shouldReceive('getModuleOperation')
            ->once()
            ->andReturn($modelPosition);
        $this->class = new ListLayout($model);
        $this->getReflectMethod('parseOperation');

        $this->assertEqualsCanonicalizing([[
            'name' => 'button1',
            'position' => 'list.tableToolbar',
        ]], $this->getPropertyValue('tableToolbar'));
        $this->assertEqualsCanonicalizing([[
            'name' => 'button2',
            'position' => 'list.batchToolbar',
        ]], $this->getPropertyValue('batchToolbar'));
    }

    public function testToArray()
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
        $model = m::mock('app\core\BaseModel');
        $model->shouldReceive('getModuleField')
            ->once()
            ->andReturn($modelField);
        $model->shouldReceive('getModuleOperation')
            ->once()
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
                        'order' => 1,
                        'position' => 'tab.main',
                        'hideInColumn' => null,
                    ],
                    [
                        'name' => 'comment',
                        'type' => 'textarea',
                        'order' => 99,
                        'position' => 'sidebar.main',
                        'hideInColumn' => true,
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
                [
                    'admin_name' => 'zhang1',
                ],
                [
                    'admin_name' => 'zhang2',
                ],
            ],
            'meta' => [
                'total' => 2,
                'per_page' => 10,
                'page' => 1,
            ],
        ];
        $this->assertEquals($expect, $this->class->setData($data)->toArray());
    }
}
