<?php

declare(strict_types=1);

namespace tests\api;

require_once('./app/api/common.php');

class CommonTest extends TestCase
{
    protected function setUp(): void
    {
        $this->startApp();
    }

    public function testGetSortParamInvalidParam()
    {
        $expect = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expect, getSortParam([], []));
        $this->assertEqualsCanonicalizing([
            'name' => 'id',
            'order' => 'desc',
        ], getSortParam([
            'sort' => 'invalidSort',
            'order' => 'invalidOrder'
        ], ['notCorrectSort']));
    }

    public function testGetSortParamValidParams()
    {
        $this->assertEqualsCanonicalizing([
            'name' => 'unitSort',
            'order' => 'desc',
        ], getSortParam([
            'sort' => 'unitSort',
            'order' => 'desc'
        ], ['unitSort']));

        $this->assertEqualsCanonicalizing([
            'name' => 'unitSort',
            'order' => 'asc',
        ], getSortParam([
            'sort' => 'unitSort',
            'order' => 'asc'
        ], ['unitSort']));
    }

    public function testGetListParamsInvalidParam()
    {
        $defaultValue = [
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
            ]
        ];
        $this->assertEqualsCanonicalizing($defaultValue, getListParams([], [], []));
    }

    public function testGetListParamsValidParam()
    {
        $actual = getListParams(['foo' => 'bar', 'sort' => 'unitSort', 'order' => 'desc'], ['foo'], ['unitSort']);

        $expect = [
            'trash' => 'withoutTrashed',
            'per_page' => 10,
            'visible' => ['foo'],
            'search' => [
                'values' => ['foo' => 'bar'],
                'keys' => ['foo'],
            ],
            'sort' => [
                'name' => 'unitSort',
                'order' => 'desc',
            ]
        ];
        $this->assertEqualsCanonicalizing($expect, $actual);
    }
}
