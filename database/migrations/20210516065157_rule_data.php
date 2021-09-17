<?php

declare(strict_types=1);

namespace DbMigrations;

use Phinx\Migration\AbstractMigration;

final class RuleData extends AbstractMigration
{
    public function up()
    {
        $currentTime = date("Y-m-d H:i:s");
        $ruleRows = [
            [
                'id' => 1,
                'parent_id'  => 0,
                'rule_path' => '',
            ],
            [
                'id' => 2,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/home',
            ],
            [
                'id' => 3,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/add',
            ],
            [
                'id' => 4,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/save',
            ],
            [
                'id' => 5,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/read',
            ],
            [
                'id' => 6,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/update',
            ],
            [
                'id' => 7,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/delete',
            ],
            [
                'id' => 8,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/restore',
            ],
            [
                'id' => 43,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/i18n',
            ],
            [
                'id' => 44,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/i18n_update',
            ],
            [
                'id' => 53,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/revision',
            ],
            [
                'id' => 54,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/revision_restore',
            ],
            [
                'id' => 9,
                'parent_id'  => 0,
                'rule_path' => '',
            ],
            [
                'id' => 10,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/home',
            ],
            [
                'id' => 11,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/add',
            ],
            [
                'id' => 12,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/save',
            ],
            [
                'id' => 13,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/read',
            ],
            [
                'id' => 14,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/update',
            ],
            [
                'id' => 15,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/delete',
            ],
            [
                'id' => 16,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/restore',
            ],
            [
                'id' => 45,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/i18n',
            ],
            [
                'id' => 46,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/i18n_update',
            ],
            [
                'id' => 55,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/revision',
            ],
            [
                'id' => 56,
                'parent_id'  => 9,
                'rule_path' => 'api/auth_group/revision_restore',
            ],
            [
                'id' => 17,
                'parent_id'  => 0,
                'rule_path' => '',
            ],
            [
                'id' => 18,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/home',
            ],
            [
                'id' => 19,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/add',
            ],
            [
                'id' => 20,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/save',
            ],
            [
                'id' => 21,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/read',
            ],
            [
                'id' => 22,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/update',
            ],
            [
                'id' => 23,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/delete',
            ],
            [
                'id' => 24,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/restore',
            ],
            [
                'id' => 47,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/i18n',
            ],
            [
                'id' => 48,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/i18n_update',
            ],
            [
                'id' => 57,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/revision',
            ],
            [
                'id' => 58,
                'parent_id'  => 17,
                'rule_path' => 'api/auth_rule/revision_restore',
            ],
            [
                'id' => 25,
                'parent_id'  => 0,
                'rule_path' => '',
            ],
            [
                'id' => 26,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/home',
            ],
            [
                'id' => 27,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/add',
            ],
            [
                'id' => 28,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/save',
            ],
            [
                'id' => 29,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/read',
            ],
            [
                'id' => 30,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/update',
            ],
            [
                'id' => 31,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/delete',
            ],
            [
                'id' => 32,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/restore',
            ],
            [
                'id' => 49,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/i18n',
            ],
            [
                'id' => 50,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/i18n_update',
            ],
            [
                'id' => 59,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/revision',
            ],
            [
                'id' => 60,
                'parent_id'  => 25,
                'rule_path' => 'api/menu/revision_restore',
            ],
            [
                'id' => 33,
                'parent_id'  => 0,
                'rule_path' => '',
            ],
            [
                'id' => 34,
                'parent_id'  => 33,
                'rule_path' => 'api/model/home',
            ],
            [
                'id' => 35,
                'parent_id'  => 33,
                'rule_path' => 'api/model/add',
            ],
            [
                'id' => 36,
                'parent_id'  => 33,
                'rule_path' => 'api/model/save',
            ],
            [
                'id' => 37,
                'parent_id'  => 33,
                'rule_path' => 'api/model/read',
            ],
            [
                'id' => 38,
                'parent_id'  => 33,
                'rule_path' => 'api/model/update',
            ],
            [
                'id' => 39,
                'parent_id'  => 33,
                'rule_path' => 'api/model/delete',
            ],
            [
                'id' => 40,
                'parent_id'  => 1,
                'rule_path' => 'api/admin/login',
            ],
            [
                'id' => 41,
                'parent_id'  => 33,
                'rule_path' => 'api/model/design',
            ],
            [
                'id' => 42,
                'parent_id'  => 33,
                'rule_path' => 'api/model/design_update',
            ],
            [
                'id' => 51,
                'parent_id'  => 33,
                'rule_path' => 'api/model/i18n',
            ],
            [
                'id' => 52,
                'parent_id'  => 33,
                'rule_path' => 'api/model/i18n_update',
            ],
            [
                'id' => 61,
                'parent_id'  => 33,
                'rule_path' => 'api/model/revision',
            ],
            [
                'id' => 62,
                'parent_id'  => 33,
                'rule_path' => 'api/model/revision_restore',
            ],
        ];

        $ruleRows = array_map(function ($item) use ($currentTime) {
            return $item + ['create_time' => $currentTime, 'update_time' => $currentTime];
        }, $ruleRows);
        $this->table('auth_rule')->insert($ruleRows)->save();

        $ruleI18nRows = [
            [
                'original_id' => 1,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin',
            ],
            [
                'original_id' => 1,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员',
            ],
            [
                'original_id' => 2,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Home',
            ],
            [
                'original_id' => 2,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员列表',
            ],
            [
                'original_id' => 3,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Add',
            ],
            [
                'original_id' => 3,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员新增',
            ],
            [
                'original_id' => 4,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Save',
            ],
            [
                'original_id' => 4,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员保存',
            ],
            [
                'original_id' => 5,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Read',
            ],
            [
                'original_id' => 5,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员读取',
            ],
            [
                'original_id' => 6,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Update',
            ],
            [
                'original_id' => 6,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员更新',
            ],
            [
                'original_id' => 7,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Delete',
            ],
            [
                'original_id' => 7,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员删除',
            ],
            [
                'original_id' => 8,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Restore',
            ],
            [
                'original_id' => 8,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员恢复',
            ],
            [
                'original_id' => 43,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin I18n',
            ],
            [
                'original_id' => 43,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员国际化',
            ],
            [
                'original_id' => 44,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin I18n Update',
            ],
            [
                'original_id' => 44,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员国际化更新',
            ],
            [
                'original_id' => 53,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Revision',
            ],
            [
                'original_id' => 53,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员版本修订',
            ],
            [
                'original_id' => 54,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Revision Restore',
            ],
            [
                'original_id' => 54,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员版本修订恢复',
            ],
            [
                'original_id' => 9,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group',
            ],
            [
                'original_id' => 9,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组',
            ],
            [
                'original_id' => 10,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Home',
            ],
            [
                'original_id' => 10,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组列表',
            ],
            [
                'original_id' => 11,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Add',
            ],
            [
                'original_id' => 11,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组新增',
            ],
            [
                'original_id' => 12,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Save',
            ],
            [
                'original_id' => 12,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组保存',
            ],
            [
                'original_id' => 13,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Read',
            ],
            [
                'original_id' => 13,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组读取',
            ],
            [
                'original_id' => 14,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Update',
            ],
            [
                'original_id' => 14,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组更新',
            ],
            [
                'original_id' => 15,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Delete',
            ],
            [
                'original_id' => 15,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组删除',
            ],
            [
                'original_id' => 16,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Restore',
            ],
            [
                'original_id' => 16,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组恢复',
            ],
            [
                'original_id' => 45,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group I18n',
            ],
            [
                'original_id' => 45,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组国际化',
            ],
            [
                'original_id' => 46,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group I18n Update',
            ],
            [
                'original_id' => 46,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组国际化更新',
            ],
            [
                'original_id' => 55,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Revision',
            ],
            [
                'original_id' => 55,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组版本修订',
            ],
            [
                'original_id' => 56,
                'lang_code' => 'en-us',
                'rule_title'  => 'Group Revision Restore',
            ],
            [
                'original_id' => 56,
                'lang_code' => 'zh-cn',
                'rule_title'  => '用户组版本修订恢复',
            ],
            [
                'original_id' => 17,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule',
            ],
            [
                'original_id' => 17,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限',
            ],
            [
                'original_id' => 18,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Home',
            ],
            [
                'original_id' => 18,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限列表',
            ],
            [
                'original_id' => 19,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Add',
            ],
            [
                'original_id' => 19,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限新增',
            ],
            [
                'original_id' => 20,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Save',
            ],
            [
                'original_id' => 20,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限保存',
            ],
            [
                'original_id' => 21,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Read',
            ],
            [
                'original_id' => 21,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限读取',
            ],
            [
                'original_id' => 22,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Update',
            ],
            [
                'original_id' => 22,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限更新',
            ],
            [
                'original_id' => 23,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Delete',
            ],
            [
                'original_id' => 23,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限删除',
            ],
            [
                'original_id' => 24,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Restore',
            ],
            [
                'original_id' => 24,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限恢复',
            ],
            [
                'original_id' => 47,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule I18n',
            ],
            [
                'original_id' => 47,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限国际化',
            ],
            [
                'original_id' => 48,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule I18n Update',
            ],
            [
                'original_id' => 48,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限国际化更新',
            ],
            [
                'original_id' => 57,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Revision',
            ],
            [
                'original_id' => 57,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限版本修订',
            ],
            [
                'original_id' => 58,
                'lang_code' => 'en-us',
                'rule_title'  => 'Rule Revision Restore',
            ],
            [
                'original_id' => 58,
                'lang_code' => 'zh-cn',
                'rule_title'  => '权限版本修订恢复',
            ],
            [
                'original_id' => 25,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu',
            ],
            [
                'original_id' => 25,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单',
            ],
            [
                'original_id' => 26,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Home',
            ],
            [
                'original_id' => 26,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单列表',
            ],
            [
                'original_id' => 27,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Add',
            ],
            [
                'original_id' => 27,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单新增',
            ],
            [
                'original_id' => 28,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Save',
            ],
            [
                'original_id' => 28,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单保存',
            ],
            [
                'original_id' => 29,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Read',
            ],
            [
                'original_id' => 29,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单读取',
            ],
            [
                'original_id' => 30,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Update',
            ],
            [
                'original_id' => 30,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单更新',
            ],
            [
                'original_id' => 31,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Delete',
            ],
            [
                'original_id' => 31,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单删除',
            ],
            [
                'original_id' => 32,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Restore',
            ],
            [
                'original_id' => 32,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单恢复',
            ],
            [
                'original_id' => 49,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu I18n',
            ],
            [
                'original_id' => 49,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单国际化',
            ],
            [
                'original_id' => 50,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu I18n Update',
            ],
            [
                'original_id' => 50,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单国际化更新',
            ],
            [
                'original_id' => 59,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Revision',
            ],
            [
                'original_id' => 59,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单版本修订',
            ],
            [
                'original_id' => 60,
                'lang_code' => 'en-us',
                'rule_title'  => 'Menu Revision Restore',
            ],
            [
                'original_id' => 60,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单版本修订恢复',
            ],
            [
                'original_id' => 33,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model',
            ],
            [
                'original_id' => 33,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型',
            ],
            [
                'original_id' => 34,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Home',
            ],
            [
                'original_id' => 34,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型列表',
            ],
            [
                'original_id' => 35,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Add',
            ],
            [
                'original_id' => 35,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型新增',
            ],
            [
                'original_id' => 36,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Save',
            ],
            [
                'original_id' => 36,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单保存',
            ],
            [
                'original_id' => 37,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Read',
            ],
            [
                'original_id' => 37,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单读取',
            ],
            [
                'original_id' => 38,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Update',
            ],
            [
                'original_id' => 38,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单更新',
            ],
            [
                'original_id' => 39,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Delete',
            ],
            [
                'original_id' => 39,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单删除',
            ],
            [
                'original_id' => 40,
                'lang_code' => 'en-us',
                'rule_title'  => 'Admin Login',
            ],
            [
                'original_id' => 40,
                'lang_code' => 'zh-cn',
                'rule_title'  => '管理员登陆',
            ],
            [
                'original_id' => 41,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Design',
            ],
            [
                'original_id' => 41,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单设计',
            ],
            [
                'original_id' => 42,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Design Update',
            ],
            [
                'original_id' => 42,
                'lang_code' => 'zh-cn',
                'rule_title'  => '菜单设计更新',
            ],
            [
                'original_id' => 51,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model I18n',
            ],
            [
                'original_id' => 51,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型国际化',
            ],
            [
                'original_id' => 52,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model I18n Update',
            ],
            [
                'original_id' => 52,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型国际化更新',
            ],
            [
                'original_id' => 61,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Revision',
            ],
            [
                'original_id' => 61,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型版本修订',
            ],
            [
                'original_id' => 62,
                'lang_code' => 'en-us',
                'rule_title'  => 'Model Revision Update',
            ],
            [
                'original_id' => 62,
                'lang_code' => 'zh-cn',
                'rule_title'  => '模型版本修订恢复',
            ],
        ];

        $ruleI18nRows = array_map(function ($item) use ($currentTime) {
            return $item + ['translate_time' => $currentTime];
        }, $ruleI18nRows);
        $this->table('auth_rule_i18n')->insert($ruleI18nRows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM auth_rule');
        $this->execute('DELETE FROM auth_rule_i18n');
    }
}
