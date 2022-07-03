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

    public function testGetAllowMethods()
    {
        $trait = new class() {
            use Modular;

            public function getDefaultConfig()
            {
                return ['built-in-field'];
            }

            public function getModuleField()
            {
                return [
                    [
                        'name' => 'admin_name',
                        'type' => 'input',
                        'unique' => true,
                        'position' => 'tab.main',
                        'order' => 0,
                        'allow' => ['index', 'view', 'add', 'filter'],
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
                        'allow' => ['view', 'add', 'edit', 'filter'],
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
                        'allow' => ['index', 'view', 'add', 'edit', 'filter', 'translate'],
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
        $this->assertEquals(['built-in-field', 'admin_name', 'display_name'], $trait->getAllow('index'));
        $this->assertEquals(['built-in-field', 'admin_name', 'password', 'display_name'], $trait->getAllow('view'));
        $this->assertEquals(['built-in-field', 'password', 'display_name'], $trait->getAllow('edit'));
        $this->assertEquals(['built-in-field', 'admin_name', 'password', 'display_name'], $trait->getAllow('add'));
        $this->assertEquals(['built-in-field', 'display_name'], $trait->getAllow('translate'));
        $this->assertEquals(['built-in-field', 'admin_name', 'password', 'display_name'], $trait->getAllow('filter'));
        // no built-in fields for 'unique' action
        $this->assertEquals(['admin_name'], $trait->getUnique());
    }

    public function testGetRequire()
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
                        'allow' => ['index', 'view', 'add', 'filter'],
                        'require' => ['update'],
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
                        'allow' => ['view', 'add', 'edit', 'filter'],
                        'require' => ['update'],
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
                        'allow' => ['index', 'view', 'add', 'edit', 'filter', 'translate'],
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
        $this->assertEquals(['admin_name', 'password'], $trait->getRequire('update'));
    }
}
