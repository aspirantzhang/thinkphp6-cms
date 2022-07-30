<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class ModuleData extends AbstractMigration
{
    public function up()
    {
        $currentTime = date('Y-m-d H:i:s');
        $field = [
            'admin_name' => [
                'type' => 'input',
                'default' => '',
                'unique' => true,
                'allow' => ['index', 'view', 'add', 'store', 'filter'],
                'require' => ['store'],
                'validation' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
            'password' => [
                'type' => 'password',
                'default' => '',
                'unique' => false,
                'allow' => ['view', 'add', 'store', 'update', 'filter'],
                'require' => ['store'],
                'validation' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
            'display_name' => [
                'type' => 'input',
                'default' => '',
                'unique' => false,
                'allow' => ['index', 'view', 'add', 'filter', 'translate'],
                'validation' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
            'comment' => [
                'type' => 'textarea',
                'default' => '',
                'unique' => false,
                'allow' => ['index', 'view', 'add', 'filter', 'translate'],
                'validation' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
        ];
        $layout = [
            'basic-list' => [
                'tableColumn' => [
                    'admin_name' => [],
                    'password' => false,
                    'display_name' => [],
                    'comment' => [],
                    'group' => [
                        'type' => 'relationModel',
                    ],
                ],
                'searchBar' => [
                    'admin_name' => [],
                    'display_name' => [],
                    'comment' => [],
                ],
                'tableToolbar' => [
                    [
                        'name' => 'add',
                        'order' => 0,
                        'type' => 'primary',
                        'call' => 'modal',
                        'method' => 'get',
                        'uri' => '/backend/admins/add',
                    ],
                ],
                'batchToolbar' => [
                    [
                        'name' => 'delete',
                        'order' => 0,
                        'type' => 'danger',
                        'call' => 'delete',
                        'method' => 'post',
                        'uri' => '/backend/admins/delete',
                    ],
                    [
                        'name' => 'disable',
                        'order' => 0,
                        'type' => 'normal',
                        'call' => 'disable',
                        'method' => 'post',
                        'uri' => '/backend/admins/disable',
                    ],
                    [
                        'name' => 'enable',
                        'order' => 0,
                        'type' => 'normal',
                        'call' => 'disable',
                        'method' => 'post',
                        'uri' => '/backend/admins/enable',
                    ],
                ],
                'batchToolbarTrashed' => [
                    [
                        'name' => 'deletePermanently',
                        'order' => 0,
                        'type' => 'danger',
                        'call' => 'delete',
                        'method' => 'post',
                        'uri' => '/backend/admins/delete',
                    ],
                ],
            ],
        ];
        $mainTableRows = [
            [
                'id' => 1,
                'table_name' => 'admin',
                'field' => json_encode($field),
                'layout' => json_encode($layout),
                'setting' => json_encode([]),
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
        ];
        $this->table('module')->insert($mainTableRows)->save();
        $i18nTableRows = [];
        $this->table('module_i18n')->insert($i18nTableRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM module');
    }
}
