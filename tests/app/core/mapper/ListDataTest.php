<?php

declare(strict_types=1);

namespace tests\app\core\Data;

use app\core\mapper\ListData;
use Mockery as m;

class ListDataTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $model = m::mock('app\core\CoreModel');
        $this->class = new ListData($model);
    }

    public function testBuildListParamsDefaultReturn()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowBrowse')
            ->once()
            ->andReturn([]);
        $this->class = new ListData($model);
        $this->class->withParams([]);
        $this->getReflectMethod('buildListParams');

        $expected = [
            'trash' => 'withoutTrashed',
            'per_page' => 10,
            'visible' => [],
            'search' => [
                'values' => [],
                'keys' => [],
            ],
            'sort' => [
                'name' => 'id',
                'order' => 'desc',
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $this->getPropertyValue('listParams'));
    }

    public function testBuildListParamsWithValidParams()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowBrowse')
            ->once()
            ->andReturn(['username', 'gender']);
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $this->class->withParams([
            'sort' => 'age',
            'order' => 'asc',
            'per_page' => 5,
            'trash' => 'onlyTrashed',
            'username' => 'zhang',
        ]);
        $this->getReflectMethod('buildListParams');
        $expected = [
            'trash' => 'onlyTrashed',
            'per_page' => 5,
            'visible' => ['username', 'gender'],
            'search' => [
                'values' => [
                    'username' => 'zhang',
                ],
                'keys' => ['username'],
            ],
            'sort' => [
                'name' => 'age',
                'order' => 'asc',
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $this->getPropertyValue('listParams'));
    }

    public function testBuildListParamsWithInvalidParams()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowBrowse')
            ->once()
            ->andReturn(['username', 'gender']);
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $this->class->withParams([
            'sort' => 'invalid-sort',
            'order' => 'invalid-order',
            'per_page' => 10,
            'trash' => 'invalid-trash',
            'invalid-search-key' => 'invalid-search-value',
        ]);
        $this->getReflectMethod('buildListParams');
        $expected = [
            'trash' => 'withoutTrashed',
            'per_page' => 10,
            'visible' => ['username', 'gender'],
            'search' => [
                'values' => [],
                'keys' => [],
            ],
            'sort' => [
                'name' => 'id',
                'order' => 'desc',
            ],
        ];
        $this->assertEqualsCanonicalizing($expected, $this->getPropertyValue('listParams'));
    }

    public function testGetSortDefaultReturn()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->andReturn([]);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSort', [[]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortWithValidParams()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSort', [[
            'sort' => 'age',
            'order' => 'asc',
        ]]);
        $expected = [
            'name' => 'age',
            'order' => 'asc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortWithInvalidSortParam()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSort', [[
            'sort' => 'name',
            'order' => 'asc',
        ]]);
        $expected = [
            'name' => 'id',
            'order' => 'asc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortWithInvalidOrderParam()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSort', [[
            'sort' => 'name',
            'order' => 'foo',
        ]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testBuildTrashParamDefaultReturn()
    {
        $this->class->withParams([]);
        $actual = $this->getReflectMethod('getTrash');

        $this->assertEquals('withoutTrashed', $actual);
    }

    public function testBuildTrashParamSpecificReturn()
    {
        $this->class->withParams(['trash' => 'withoutTrashed']);
        $actual = $this->getReflectMethod('getTrash');
        $this->assertEquals('withoutTrashed', $actual);

        $this->class->withParams(['trash' => 'onlyTrashed']);
        $actual = $this->getReflectMethod('getTrash');
        $this->assertEquals('onlyTrashed', $actual);

        $this->class->withParams(['trash' => 'withTrashed']);
        $actual = $this->getReflectMethod('getTrash');
        $this->assertEquals('withTrashed', $actual);
    }
}
