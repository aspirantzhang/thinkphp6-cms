<?php

declare(strict_types=1);

namespace tests\app\model;

use app\core\model\Modular;

class ModularTest extends \tests\TestCase
{
    public function testGetTableName()
    {
        $trait = new class() {
            public string $name;
            use Modular;
        };
        $trait->name = 'UnitTest';
        $this->assertEquals('unit_test', $trait->getTableName());
        $trait->name = 'unit test';
        $this->assertEquals('unit_test', $trait->getTableName());
        $trait->name = 'unitTest';
        $this->assertEquals('unit_test', $trait->getTableName());
    }

    public function testGetFieldSetWithSpecificPropertyDefaultReturn()
    {
        $trait = new class() {
            use Modular;

            public function getModuleField()
            {
                return [];
            }
        };
        $this->assertEquals([], $trait->getFieldSetWithSpecificProperty('whatever'));
    }

    public function testGetFieldSetWithSpecificPropertyValidReturn()
    {
        $trait = new class() {
            use Modular;

            public function getModuleField()
            {
                return [
                    [
                        'name' => 'admin_name',
                        'type' => 'input',
                        'unique' => true,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => [
                            'browse' => true,
                            'read' => true,
                            'add' => true,
                            'edit' => false,
                            'filter' => true,
                            'translate' => false,
                        ],
                        'validate' => [
                            'required' => true,
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                    [
                        'name' => 'password',
                        'type' => 'password',
                        'unique' => false,
                        'position' => 'tab.main',
                        'order' => 0,
                        'hideInColumn' => true,
                        'allow' => [
                            'browse' => false,
                            'read' => true,
                            'add' => true,
                            'edit' => true,
                            'filter' => true,
                            'translate' => false,
                        ],
                        'validate' => [
                            'required' => true,
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                    [
                        'name' => 'display_name',
                        'type' => 'input',
                        'unique' => false,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => [
                            'browse' => true,
                            'read' => true,
                            'add' => true,
                            'edit' => true,
                            'filter' => true,
                            'translate' => true,
                        ],
                        'validate' => [
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                    [
                        'name' => 'age',
                        'type' => 'integer',
                        'unique' => false,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => [
                            'browse' => true,
                            'read' => true,
                            'add' => true,
                            'edit' => true,
                            'sort' => true,
                            'filter' => true,
                            'translate' => false,
                        ],
                        'validate' => [
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                ];
            }
        };
        $this->assertEquals([], $trait->getFieldSetWithSpecificProperty('not-exist-property'));
        $this->assertEquals(['admin_name'], $trait->getFieldSetWithSpecificProperty('unique'));
        $this->assertEquals(['admin_name', 'password', 'display_name', 'age'], $trait->getFieldSetWithSpecificProperty('allow.filter'));
        $this->assertEquals(['display_name'], $trait->getFieldSetWithSpecificProperty('allow.translate'));
        $this->assertEquals(['admin_name', 'display_name', 'age'], $trait->getFieldSetWithSpecificProperty('allow.browse'));
        $this->assertEquals(['admin_name', 'password', 'display_name', 'age'], $trait->getFieldSetWithSpecificProperty('allow.read'));
        $this->assertEquals(['password', 'display_name', 'age'], $trait->getFieldSetWithSpecificProperty('allow.edit'));
        $this->assertEquals(['admin_name', 'password', 'display_name', 'age'], $trait->getFieldSetWithSpecificProperty('allow.add'));
        $this->assertEquals(['age'], $trait->getFieldSetWithSpecificProperty('allow.sort'));
    }

    public function testGetFieldFunctionSet()
    {
        $trait = new class() {
            use Modular;

            public function getModuleField()
            {
                return [
                    [
                        'name' => 'admin_name',
                        'type' => 'input',
                        'unique' => true,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => [
                            'browse' => true,
                            'read' => true,
                            'add' => true,
                            'edit' => false,
                            'filter' => true,
                            'translate' => false,
                        ],
                        'validate' => [
                            'required' => true,
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                    [
                        'name' => 'password',
                        'type' => 'password',
                        'unique' => false,
                        'position' => 'tab.main',
                        'order' => 0,
                        'hideInColumn' => true,
                        'allow' => [
                            'browse' => false,
                            'read' => true,
                            'add' => true,
                            'edit' => true,
                            'filter' => true,
                            'translate' => false,
                        ],
                        'validate' => [
                            'required' => true,
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                    [
                        'name' => 'display_name',
                        'type' => 'input',
                        'unique' => false,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => [
                            'browse' => true,
                            'read' => true,
                            'add' => true,
                            'edit' => true,
                            'filter' => true,
                            'translate' => true,
                        ],
                        'validate' => [
                            'length' => [
                                'min' => 0,
                                'max' => 255,
                            ],
                        ],
                    ],
                ];
            }
        };
        $this->assertEquals(['admin_name', 'display_name'], $trait->getAllowBrowse());
        $this->assertEquals(['admin_name', 'password', 'display_name'], $trait->getAllowRead());
        $this->assertEquals(['password', 'display_name'], $trait->getAllowEdit());
        $this->assertEquals(['admin_name', 'password', 'display_name'], $trait->getAllowAdd());
        $this->assertEquals(['display_name'], $trait->getAllowTranslate());
        $this->assertEquals(['admin_name', 'password', 'display_name'], $trait->getAllowFilter());
        $this->assertEquals(['admin_name'], $trait->getUnique());
    }
}
