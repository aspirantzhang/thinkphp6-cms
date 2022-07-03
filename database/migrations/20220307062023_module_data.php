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
            [
                'name' => 'admin_name',
                'type' => 'input',
                'unique' => true,
                'position' => 'tab.main',
                'order' => 0,
                'allow' => ['index', 'view', 'add', 'store', 'edit', 'filter'],
                'require' => ['store'],
                'rule' => [
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
                'allow' => ['view', 'add', 'store', 'edit', 'update', 'filter'],
                'require' => ['store'],
                'rule' => [
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
                'allow' => ['index', 'view', 'add', 'store', 'edit', 'update', 'filter', 'translate'],
                'rule' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
            [
                'name' => 'comment',
                'type' => 'textarea',
                'unique' => false,
                'order' => 0,
                'position' => 'sidebar.main',
                'hideInColumn' => true,
                'allow' => ['index', 'view', 'add', 'store', 'edit', 'update', 'filter', 'translate'],
                'rule' => [
                    'length' => [
                        'min' => 0,
                        'max' => 255,
                    ],
                ],
            ],
        ];
        $operation = [
            [
                'name' => 'add',
                'position' => 'list.tableToolbar',
                'order' => 0,
                'type' => 'primary',
                'call' => 'modal',
                'method' => 'get',
                'uri' => '/backend/admins/add',
            ],
            [
                'name' => 'delete',
                'position' => 'list.batchToolbar',
                'order' => 0,
                'type' => 'danger',
                'call' => 'delete',
                'method' => 'post',
                'uri' => '/backend/admins/delete',
            ],
            [
                'name' => 'disable',
                'position' => 'list.batchToolbar',
                'order' => 0,
                'type' => 'normal',
                'call' => 'disable',
                'method' => 'post',
                'uri' => '/backend/admins/disable',
            ],
        ];
        $mainTableRows = [
            [
                'id' => 1,
                'table_name' => 'admin',
                'field' => json_encode($field),
                'operation' => json_encode($operation),
                'setting' => json_encode([]),
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
        ];
        $this->table('module')->insert($mainTableRows)->save();
        $i18nTableRows = [
            [
                'original_id' => 1,
                'lang_code' => 'en-us',
                'module_title' => 'Admin',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'module_title' => '管理员',
                'translate_time' => $currentTime,
            ],
        ];
        $this->table('module_i18n')->insert($i18nTableRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM module');
    }
}
