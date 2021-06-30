<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class MenuData extends AbstractMigration
{
    public function up()
    {
        $currentTime = date("Y-m-d H:i:s");
        $menuRows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'path' => '/basic-list/api/admins',
                'icon' => 'icon-user',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 2,
                'parent_id' => 1,
                'path' => '/basic-list/api/admins/add',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 3,
                'parent_id' => 1,
                'path' => '/basic-list/api/admins/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'path' => '/basic-list/api/groups',
                'icon' => 'icon-team',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 5,
                'parent_id' => 4,
                'path' => '/basic-list/api/groups/add',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 6,
                'parent_id' => 4,
                'path' => '/basic-list/api/groups/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 7,
                'parent_id' => 0,
                'path' => '/basic-list/api/rules',
                'icon' => 'icon-table',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 8,
                'parent_id' => 7,
                'path' => '/basic-list/api/rules/add',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 9,
                'parent_id' => 7,
                'path' => '/basic-list/api/rules/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'path' => '/basic-list/api/menus',
                'icon' => 'icon-menu',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 11,
                'parent_id' => 10,
                'path' => '/basic-list/api/menus/add',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 12,
                'parent_id' => 10,
                'path' => '/basic-list/api/menus/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 13,
                'parent_id' => 0,
                'path' => '/basic-list/api/models',
                'icon' => 'icon-appstore',
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 14,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/add',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 15,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
            [
                'id' => 16,
                'parent_id' => 13,
                'path' => '/basic-list/api/models/design/:id',
                'hide_in_menu' => 1,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ],
        ];
        $this->table('menu')->insert($menuRows)->save();

        $menuI18nRows = [
            [
                'original_id' => 1,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin List',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 2,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin Add',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 3,
                'lang_code' => 'en-us',
                'menu_title' => 'Admin Edit',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 4,
                'lang_code' => 'en-us',
                'menu_title' => 'Group List',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 5,
                'lang_code' => 'en-us',
                'menu_title' => 'Group Add',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 6,
                'lang_code' => 'en-us',
                'menu_title' => 'Group Edit',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 7,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule List',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 8,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule Add',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 9,
                'lang_code' => 'en-us',
                'menu_title' => 'Rule Edit',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 10,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu List',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 11,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu Add',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 12,
                'lang_code' => 'en-us',
                'menu_title' => 'Menu Edit',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 13,
                'lang_code' => 'en-us',
                'menu_title' => 'Model List',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 14,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Add',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 15,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Edit',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 16,
                'lang_code' => 'en-us',
                'menu_title' => 'Model Design',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员列表',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 2,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员添加',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 3,
                'lang_code' => 'zh-cn',
                'menu_title' => '管理员编辑',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 4,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组列表',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 5,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组添加',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 6,
                'lang_code' => 'zh-cn',
                'menu_title' => '用户组编辑',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 7,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限列表',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 8,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限添加',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 9,
                'lang_code' => 'zh-cn',
                'menu_title' => '权限编辑',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 10,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单列表',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 11,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单添加',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 12,
                'lang_code' => 'zh-cn',
                'menu_title' => '菜单编辑',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 13,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型列表',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 14,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型添加',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 15,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型编辑',
                'translate_time' => $currentTime,
            ],
            [
                'original_id' => 16,
                'lang_code' => 'zh-cn',
                'menu_title' => '模型设计',
                'translate_time' => $currentTime,
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
