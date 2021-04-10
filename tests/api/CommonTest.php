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

    public function testArrayToTreeInvalidParam()
    {
        $this->assertEqualsCanonicalizing([], arrayToTree([]));
        $this->assertEqualsCanonicalizing([], arrayToTree([]));
        $this->assertEqualsCanonicalizing([], arrayToTree(0));
        $this->assertEqualsCanonicalizing([], arrayToTree(''));
        $this->assertEqualsCanonicalizing([], arrayToTree(null));
        $this->assertEqualsCanonicalizing([], arrayToTree("\t"));
        $this->assertEqualsCanonicalizing([], arrayToTree("\n"));
        $this->assertEqualsCanonicalizing([], arrayToTree("\r"));
        $this->assertEqualsCanonicalizing([], arrayToTree(' '));
        $this->assertEqualsCanonicalizing([], arrayToTree(true));
        $this->assertEqualsCanonicalizing([], arrayToTree(false));
    }

    public function testArrayToTreeValidParam()
    {
        $actual = arrayToTree([
            [ 'id' => 1, 'parent_id' => 0 ],
            [ 'id' => 2, 'parent_id' => 0 ],
            [ 'id' => 3, 'parent_id' => 1 ],
            [ 'id' => 4, 'parent_id' => 3 ],
        ]);
        $expect = [
            [ 'id' => 1, 'parent_id' => 0, 'depth' => 1 , 'children' => [
                [ 'id' => 3, 'parent_id' => 1, 'depth' => 2, 'children' => [
                    [ 'id' => 4, 'parent_id' => 3, 'depth' => 3 ]
                ]]
            ]],
            [ 'id' => 2, 'parent_id' => 0, 'depth' => 1 ]
        ];
        $this->assertEqualsCanonicalizing($expect, $actual);
    }

    public function testArrayToTreeIfNoParent()
    {
        $actual = arrayToTree([
            [ 'id' => 1, 'parent_id' => 0 ],
            [ 'id' => 2, 'parent_id' => 0 ],
            [ 'id' => 3, 'parent_id' => 99999 ],
            [ 'id' => 4, 'parent_id' => 1 ]
        ]);
        $expect = [
            [ 'id' => 1, 'parent_id' => 0, 'depth' => 1 , 'children' => [
                [ 'id' => 4, 'parent_id' => 1, 'depth' => 2 ]
            ]],
            [ 'id' => 2, 'parent_id' => 0, 'depth' => 1 ],
            [ 'id' => 3, 'parent_id' => 0, 'depth' => 1 ]
        ];
        var_dump($actual);
        var_dump($expect);

        $this->assertEqualsCanonicalizing($expect, $actual);
    }

    public function testArrayToTreeIfNoRoot()
    {
        $actual = arrayToTree([
            [ 'id' => 1, 'parent_id' => 0 ],
            [ 'id' => 2, 'parent_id' => 0 ],
            [ 'id' => 3, 'parent_id' => 1 ],
        ], 99);
        $expect = [];

        $this->assertEqualsCanonicalizing($expect, $actual);
    }

    public function testArrayToTreeIfParentIdLessThanZero()
    {
        $actual = arrayToTree([
            [ 'id' => 1, 'parent_id' => 0 ],
            [ 'id' => 2, 'parent_id' => 1 ],
            [ 'id' => 0, 'parent_id' => -1 ],
            [ 'id' => 3, 'parent_id' => 999 ],
        ], -1);
        $expect = [
            [ 'id' => 0, 'parent_id' => -1, 'depth' => 1, 'children' => [
                [ 'id' => 1, 'parent_id' => 0, 'depth' => 2 , 'children' => [
                    [ 'id' => 2, 'parent_id' => 1, 'depth' => 3 ]
                ]],
                [ 'id' => 3, 'parent_id' => 0, 'depth' => 2 ]
            ]]
        ];

        $this->assertEqualsCanonicalizing($expect, $actual);
    }
}
