<?php

declare(strict_types=1);

namespace tests\app\core\Data;

use app\core\domain\Data\ListData;
use Mockery as m;

class ListDataTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $model = m::mock('app\core\CoreModel');
        $this->class = new ListData($model);
    }

    public function testGetListParamsDefaultReturn()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowBrowse')
            ->once()
            ->andReturn([]);
        $this->class = new ListData($model);
        $this->class->withParams([]);
        $this->getReflectMethod('getListParams');

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

    public function testGetListParamsWithValidParams()
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
        $this->getReflectMethod('getListParams');
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

    public function testGetListParamsWithInvalidParams()
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
        $this->getReflectMethod('getListParams');
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

    public function testGetSortParamDefaultReturn()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->andReturn([]);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSortParam', [[]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortParamWithValidParams()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSortParam', [[
            'sort' => 'age',
            'order' => 'asc',
        ]]);
        $expected = [
            'name' => 'age',
            'order' => 'asc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortParamWithInvalidSortParam()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSortParam', [[
            'sort' => 'name',
            'order' => 'asc',
        ]]);
        $expected = [
            'name' => 'id',
            'order' => 'asc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortParamWithInvalidOrderParam()
    {
        $model = m::mock('app\backend\model\Admin');
        $model->shouldReceive('getAllowSort')
            ->once()
            ->andReturn(['age']);
        $this->class = new ListData($model);
        $actual = $this->getReflectMethod('getSortParam', [[
            'sort' => 'name',
            'order' => 'foo',
        ]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetTrashParamDefaultReturn()
    {
        $this->class->withParams([]);
        $actual = $this->getReflectMethod('getTrashParam');

        $this->assertEquals('withoutTrashed', $actual);
    }

    public function testGetTrashParamSpecificReturn()
    {
        $this->class->withParams(['trash' => 'withoutTrashed']);
        $actual = $this->getReflectMethod('getTrashParam');
        $this->assertEquals('withoutTrashed', $actual);

        $this->class->withParams(['trash' => 'onlyTrashed']);
        $actual = $this->getReflectMethod('getTrashParam');
        $this->assertEquals('onlyTrashed', $actual);

        $this->class->withParams(['trash' => 'withTrashed']);
        $actual = $this->getReflectMethod('getTrashParam');
        $this->assertEquals('withTrashed', $actual);
    }
}
