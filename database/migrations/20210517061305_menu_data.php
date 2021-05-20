<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class MenuData extends AbstractMigration
{
    public function up()
    {
        $menuRows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'path' => '/basic-list/api/admins',
                'icon' => 'icon-user',
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 2,
                'parent_id' => 1,
                'path' => '/basic-list/api/admins/add',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 3,
                'parent_id' => 1,
                'path' => '/basic-list/api/admins/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'path' => '/basic-list/api/groups',
                'icon' => 'icon-team',
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 5,
                'parent_id' => 4,
                'path' => '/basic-list/api/groups/add',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 6,
                'parent_id' => 4,
                'path' => '/basic-list/api/groups/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 7,
                'parent_id' => 0,
                'path' => '/basic-list/api/rules',
                'icon' => 'icon-table',
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 8,
                'parent_id' => 7,
                'path' => '/basic-list/api/rules/add',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 9,
                'parent_id' => 7,
                'path' => '/basic-list/api/rules/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'path' => '/basic-list/api/menus',
                'icon' => 'icon-menu',
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 11,
                'parent_id' => 10,
                'path' => '/basic-list/api/menus/add',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 12,
                'parent_id' => 10,
                'path' => '/basic-list/api/menus/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 13,
                'parent_id' => 0,
                'path' => '/basic-list/api/models',
                'icon' => 'icon-appstore',
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 14,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/add',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 15,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 16,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/design/:id',
                'hide_in_menu' => 1,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->table('menu')->insert($menuRows)->save();

        $menuI18nRows = [
            [
                '_id' => 1,
                'original_id' => 1,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin List',
            ],
            [
                '_id' => 2,
                'original_id' => 2,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin Add',
            ],
            [
                '_id' => 3,
                'original_id' => 3,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin Edit',
            ],
            [
                '_id' => 4,
                'original_id' => 4,
                'lang_code' => 'en-us',
                'menu_title' => 'Group List',
            ],
            [
                '_id' => 5,
                'original_id' => 5,
                'lang_code' => 'en-us',
                'menu_title' => 'Group Add',
            ],
            [
                '_id' => 6,
                'original_id' => 6,
                'lang_code' => 'en-us',
                'menu_title' => 'Group Edit',
            ],
            [
                '_id' => 7,
                'original_id' => 7,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule List',
            ],
            [
                '_id' => 8,
                'original_id' => 8,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule Add',
            ],
            [
                '_id' => 9,
                'original_id' => 9,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule Edit',
            ],
            [
                '_id' => 10,
                'original_id' => 10,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu List',
            ],
            [
                '_id' => 11,
                'original_id' => 11,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu Add',
            ],
            [
                '_id' => 12,
                'original_id' => 12,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu Edit',
            ],
            [
                '_id' => 13,
                'original_id' => 13,
                'lang_code' => 'en-us',
                'menu_title' => 'Model List',
            ],
            [
                '_id' => 14,
                'original_id' => 14,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Add',
            ],
            [
                '_id' => 15,
                'original_id' => 15,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Edit',
            ],
            [
                '_id' => 16,
                'original_id' => 16,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Design',
            ],
            [
                '_id' => 17,
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员列表',
            ],
            [
                '_id' => 18,
                'original_id' => 2,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员添加',
            ],
            [
                '_id' => 19,
                'original_id' => 3,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员编辑',
            ],
            [
                '_id' => 20,
                'original_id' => 4,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组列表',
            ],
            [
                '_id' => 21,
                'original_id' => 5,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组添加',
            ],
            [
                '_id' => 22,
                'original_id' => 6,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组编辑',
            ],
            [
                '_id' => 23,
                'original_id' => 7,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限列表',
            ],
            [
                '_id' => 24,
                'original_id' => 8,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限添加',
            ],
            [
                '_id' => 25,
                'original_id' => 9,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限编辑',
            ],
            [
                '_id' => 26,
                'original_id' => 10,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单列表',
            ],
            [
                '_id' => 27,
                'original_id' => 11,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单添加',
            ],
            [
                '_id' => 28,
                'original_id' => 12,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单编辑',
            ],
            [
                '_id' => 29,
                'original_id' => 13,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型列表',
            ],
            [
                '_id' => 30,
                'original_id' => 14,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型添加',
            ],
            [
                '_id' => 31,
                'original_id' => 15,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型编辑',
            ],
            [
                '_id' => 32,
                'original_id' => 16,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型设计',
            ],
        ];
        $this->table('menu_i18n')->insert($menuI18nRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM menu');
        $this->execute('DELETE FROM menu_i18n');
    }
}
