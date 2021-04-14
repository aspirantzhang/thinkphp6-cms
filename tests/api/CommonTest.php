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

    public function testExtractValuesInvalidParam()
    {
        $this->assertEqualsCanonicalizing([], extractValues([]));
        $this->assertEqualsCanonicalizing([], extractValues([], 'whatever...', 'whatever...'));
        $actual = extractValues([
            [ 'unit' => 'test1', 'other' => '...' ],
            [ 'unit' => 'test2', 'other' => '...' ],
            [ 'unit' => 'test3', 'other' => '...' ],
        ], 'unknown');
        $expect = [];
        $this->assertEqualsCanonicalizing($expect, $actual);
    }

    public function testExtractValuesValidParam()
    {
        $actual = extractValues([
            [ 'unit' => 'test1', 'other' => '...' ],
            [ 'unit' => 'test2', 'other' => '...' ],
        ], 'unit');
        $expect = ['test1', 'test2'];
        $this->assertEqualsCanonicalizing($expect, $actual);

        $actual2 = extractValues([
            [
                'data' => [
                    [ 'unit' => 'test1', 'other' => '...' ],
                    [ 'unit' => 'test2', 'other' => '...' ],
                ],
            ],
            [
                'data' => [
                    [ 'unit' => 'test3', 'other' => '...' ],
                    [ 'unit' => 'test2', 'other' => '...' ],
                ],
            ],
        ], 'unit', 'data');
        $expect2 = ['test1', 'test2', 'test3'];
        $this->assertEqualsCanonicalizing($expect2, $actual2);

        $actual3 = extractValues([
            [
                'data' => [
                    [ 'unit' => 'test1', 'other' => '...' ],
                    [ 'unit' => 'test2', 'other' => '...' ],
                ],
            ],
            [
                'data' => [
                    [ 'unit' => 'test3', 'other' => '...' ],
                    [ 'unit' => 'test2', 'other' => '...' ],
                ],
            ],
        ], 'unit', 'data', false);
        $expect3 = ['test1', 'test2', 'test3', 'test2'];
        $this->assertEqualsCanonicalizing($expect3, $actual3);

        $actual4 = extractValues([
            [
                'data' => [
                    'unit' => 'test1'
                ],
            ],
            [
                'data' => [
                    'unit' => 'test2'
                ],
            ],
        ], 'unit', 'data');
        $expect4 = ['test1', 'test2'];
        $this->assertEqualsCanonicalizing($expect4, $actual4);

        $actual5 = extractValues([
            [
                'data' => [
                    'unit' => ['value1','value2']
                ],
            ],
            [
                'data' => [
                    'unit' => ['value3','value4']
                ],
            ],
        ], 'unit', 'data');
        $expect5 = [['value1','value2'], ['value3','value4']];
        $this->assertEqualsCanonicalizing($expect5, $actual5);

        $actual6 = extractValues([
            [ 'unit' => 'test1', 'other' => '...' ],
            [ 'unit' => 'test1', 'other' => '...' ],
        ], 'unit', '', false);
        $expect6 = ['test1', 'test1'];
        $this->assertEqualsCanonicalizing($expect6, $actual6);
    }

    public function testGetDescendantSetInvalidParam()
    {
        $actual = getDescendantSet('', '', '');
        $expect = [];
        $this->assertEqualsCanonicalizing($expect, $actual);
    }

    public function testGetDescendantSetValidParam()
    {
        $array = [
            [
                'id' => 1,
                'name' => 'one',
                'children' => [
                    [
                        'id' => 2,
                        'name' => 'two',
                        'children' => [
                            [
                                'id' => 3,
                                'name' => 'three',
                                'children' => [
                                    [
                                        'id' => 4,
                                        'name' => 'four',
                                    ],
                                    [
                                        'id' => 5,
                                        'name' => 'five',
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $actual1 = getDescendantSet('id', 'name', 'two', $array);
        $expect1 = [3, 4, 5];
        $this->assertEqualsCanonicalizing($expect1, $actual1);

        $actual2 = getDescendantSet('id', 'name', 'two', $array, false);
        $expect2 = [3];
        $this->assertEqualsCanonicalizing($expect2, $actual2);

        $actual3 = getDescendantSet('id', 'name', 'four', $array);
        $expect3 = [4];
        $this->assertEqualsCanonicalizing($expect3, $actual3);

        $actual4 = getDescendantSet('id', 'name', 'unknown', $array);
        $expect4 = [];
        $this->assertEqualsCanonicalizing($expect4, $actual4);

        $actual5 = getDescendantSet('id', 'name', 'unknown', $array, false);
        $expect5 = [];
        $this->assertEqualsCanonicalizing($expect5, $actual5);
    }
}
