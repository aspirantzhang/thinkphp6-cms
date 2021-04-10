<?php

declare(strict_types=1);

namespace tests\api;

require_once('./app/api/common.php');


class CommonTest extends TestCase
{
    public function testInvalidDatetimeShouldReturnFalse()
    {
        $this->assertFalse(validateDateTime(''));
        $this->assertFalse(validateDateTime(0));
        $this->assertFalse(validateDateTime([]));
        $this->assertFalse(validateDateTime(null));
        $this->assertFalse(validateDateTime("\t"));
        $this->assertFalse(validateDateTime("\n"));
        $this->assertFalse(validateDateTime("\r"));
        $this->assertFalse(validateDateTime(' '));
        $this->assertFalse(validateDateTime(true));
        $this->assertFalse(validateDateTime(false));
    }

    public function testValidDatetimeShouldReturnTrue()
    {
        $this->assertTrue(validateDateTime('2020-04-02 11:59:59'));
    }

    public function testGetSortParamInvalidParam()
    {
        $expect = [
            'name' => 'id',
            'order' => 'desc',
        ];
        $this->assertEqualsCanonicalizing($expect, getSortParam([], []));
        $this->assertEqualsCanonicalizing($expect, getSortParam(0, 0));
        $this->assertEqualsCanonicalizing($expect, getSortParam('', ''));
        $this->assertEqualsCanonicalizing($expect, getSortParam(null, null));
        $this->assertEqualsCanonicalizing($expect, getSortParam("\t", "\t"));
        $this->assertEqualsCanonicalizing($expect, getSortParam("\n", "\n"));
        $this->assertEqualsCanonicalizing($expect, getSortParam("\r", "\r"));
        $this->assertEqualsCanonicalizing($expect, getSortParam(' ', ' '));
        $this->assertEqualsCanonicalizing($expect, getSortParam(true, true));
        $this->assertEqualsCanonicalizing($expect, getSortParam(false, false));
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

    public function testGetSearchParamInvalidParam()
    {
        $this->assertEqualsCanonicalizing([], getSearchParam([], []));
        $this->assertEqualsCanonicalizing([], getSearchParam(0, 0));
        $this->assertEqualsCanonicalizing([], getSearchParam('', ''));
        $this->assertEqualsCanonicalizing([], getSearchParam(null, null));
        $this->assertEqualsCanonicalizing([], getSearchParam("\t", "\t"));
        $this->assertEqualsCanonicalizing([], getSearchParam("\n", "\n"));
        $this->assertEqualsCanonicalizing([], getSearchParam("\r", "\r"));
        $this->assertEqualsCanonicalizing([], getSearchParam(' ', ' '));
        $this->assertEqualsCanonicalizing([], getSearchParam(true, true));
        $this->assertEqualsCanonicalizing([], getSearchParam(false, false));
        $this->assertEqualsCanonicalizing([], getSearchParam([
            'notAllowField' => 'unit test',
        ], ['allowField']));
    }

    public function testGetSearchParamValidParam()
    {
        $this->assertEqualsCanonicalizing([
            'allowField1' => 'unit test 1',
            'allowField2' => 'unit test 2',
        ], getSearchParam([
            'allowField1' => 'unit test 1',
            'allowField2' => 'unit test 2',
            'notAllow' => 'unit test 3',
        ], ['allowField1', 'allowField2']));
    }
}
