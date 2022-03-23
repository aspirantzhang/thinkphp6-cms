<?php

declare(strict_types=1);

namespace tests\app\core\Lists;

use app\core\domain\Lists\Lists;
use Mockery as m;
use ReflectionClass;

class ListsTest extends \tests\TestCase
{
    public function setUp(): void
    {
        $model = m::mock('app\core\model\Model');
        $this->class = new Lists($model);
        $this->reflector = new ReflectionClass($this->class);
    }

    public function testGetListParamsDefaultReturn()
    {
        $model = m::mock('app\backend\model\Admin');
        $model::$config = ['allowHome' => []];
        $this->class = new Lists($model);
        $this->class->withParams([]);
        $method = $this->reflector->getMethod('getListParams');
        $method->setAccessible(true);
        $method->invokeArgs($this->class, []);
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
        $model::$config = ['allowHome' => ['username', 'gender'], 'allowSort' => ['age']];
        $this->class = new Lists($model);
        $this->class->withParams([
            'sort' => 'age',
            'order' => 'asc',
            'per_page' => 5,
            'trash' => 'onlyTrashed',
            'username' => 'zhang',
        ]);
        $this->getMethodInvoke('getListParams');
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
        $model::$config = ['allowHome' => ['username', 'gender'], 'allowSort' => ['age']];
        $this->class = new Lists($model);
        $this->class->withParams([
            'sort' => 'invalid-sort',
            'order' => 'invalid-order',
            'per_page' => 10,
            'trash' => 'invalid-trash',
            'invalid-search-key' => 'invalid-search-value',
        ]);
        $this->getMethodInvoke('getListParams');
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
        $model::$config = ['allowSort' => []];
        $this->class = new Lists($model);
        $method = $this->reflector->getMethod('getSortParam');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($this->class, [[]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }

    public function testGetSortParamWithValidParams()
    {
        $model = m::mock('app\backend\model\Admin');
        $model::$config = ['allowSort' => ['age']];
        $this->class = new Lists($model);
        $method = $this->reflector->getMethod('getSortParam');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($this->class, [[
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
        $model::$config = ['allowSort' => ['age']];
        $this->class = new Lists($model);
        $method = $this->reflector->getMethod('getSortParam');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($this->class, [[
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
        $model::$config = ['allowSort' => ['age']];
        $this->class = new Lists($model);
        $method = $this->reflector->getMethod('getSortParam');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($this->class, [[
            'sort' => 'name',
            'order' => 'foo',
        ]]);
        $expected = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expected, $actual);
    }
}
