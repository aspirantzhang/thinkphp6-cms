<?php

declare(strict_types=1);

namespace app\api\logic;

use app\api\view\Model as ModelView;
use app\api\service\AuthRule as RuleService;
use app\api\service\Menu as MenuService;
use think\facade\Db;
use think\facade\Console;
use think\facade\Config;
use think\helper\Str;

class Model extends ModelView
{
    protected function existsTable($tableName)
    {
        try {
            Db::query("select 1 from `$tableName` LIMIT 1");
        } catch (\Exception $e) {
            $this->error = "Table not found.";
            return false;
        }
        return true;
    }

    protected function createModelFile(string $tableName, string $routeName)
    {
        try {
            Console::call('make:buildModel', [Str::studly($tableName), '--route=' . $routeName]);
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Create model file failed.';
            return false;
        }
    }

    protected function removeModelFile(string $tableName)
    {
        try {
            Console::call('make:removeModel', [Str::studly($tableName)]);
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Remove model file failed.';
            return false;
        }
    }

    protected function createTable(string $tableName)
    {
        try {
            Db::execute("CREATE TABLE `$tableName` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `create_time` DATETIME NOT NULL , `update_time` DATETIME NOT NULL , `delete_time` DATETIME NULL DEFAULT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $i18nTable = $tableName . '_i18n';
            Db::execute("CREATE TABLE `$i18nTable` ( `_id` int unsigned NOT NULL AUTO_INCREMENT , `original_id` int unsigned NOT NULL , `lang_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', PRIMARY KEY (`_id`), UNIQUE KEY `original_id` (`original_id`,`lang_code`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Create table failed.';
            return false;
        }
    }

    // TODO: add transaction
    protected function removeTable(string $tableName)
    {
        try {
            Db::execute("DROP TABLE IF EXISTS `$tableName`");
            $i18nTable = $tableName . '_i18n';
            Db::execute("DROP TABLE IF EXISTS `$i18nTable`");
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Remove table failed.';
            return false;
        }
    }

    protected function createSelfRule(string $tableTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $rule = (new RuleService())->saveAPI([
            'parent_id' => 0,
            'rule_title' => $tableTitle,
            'create_time' => $currentTime,
            'update_time' => $currentTime,
        ]);
        return $rule[0]['data']['id'];
    }

    protected function removeSelfRule(string $tableTitle)
    {
        $rule = RuleService::where('rule_title', $tableTitle)->find();
        $rule->startTrans();
        try {
            $ruleId = $rule->id;
            $rule->force()->delete();
            $rule->commit();
            return (int)$ruleId;
        } catch (\Throwable $e) {
            $this->error = 'Remove self rule failed.';
            $rule->rollback();
            return false;
        }
    }

    protected function createChildrenRule(int $ruleId, string $tableTitle, string $tableName)
    {
        $rule = new RuleService();
        $currentTime = date("Y-m-d H:i:s");
        $rule->startTrans();
        try {
            $initRules = [
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Home', 'rule_path' => 'api/' . $tableName . '/home', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Add', 'rule_path' => 'api/' . $tableName . '/add', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Save', 'rule_path' => 'api/' . $tableName . '/save', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Read', 'rule_path' => 'api/' . $tableName . '/read', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Update', 'rule_path' => 'api/' . $tableName . '/update', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Delete', 'rule_path' => 'api/' . $tableName . '/delete', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Restore', 'rule_path' => 'api/' . $tableName . '/restore', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ];
            $rule->saveAll($initRules);
            $rule->commit();
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Create children rule failed.';
            $rule->rollback();
            return false;
        }
    }

    protected function removeChildrenRule(int $ruleId)
    {
        $rule = new RuleService();
        $rule->startTrans();
        try {
            $rulesData = $rule->where('parent_id', $ruleId)->select();
            if (!$rulesData->isEmpty()) {
                foreach ($rulesData as $item) {
                    $item->force()->delete();
                }
            }
            $rule->commit();
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Remove children rule failed.';
            $rule->rollback();
            return false;
        }
    }

    //TODO: saveApi
    protected function createSelfMenu(string $routeName)
    {
        $menu = new MenuService();
        $currentTime = date("Y-m-d H:i:s");
        $menu->startTrans();
        try {
            $menu->save([
                'parent_id' => 0,
                'title' => $routeName . '-list',
                'icon' => 'icon-project',
                'path' => '/basic-list/api/' . $routeName,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
            ]);
            $menu->commit();
            return (int)$menu->id;
        } catch (\Throwable $e) {
            $this->error = 'Create self menu failed.';
            $menu->rollback();
            return false;
        }
    }

    protected function removeSelfMenu(string $routeName)
    {
        $menu = MenuService::where('title', $routeName . '-list')->find();
        $menu->startTrans();
        try {
            $menuId = $menu->id;
            $menu->force()->delete();
            $menu->commit();
            return (int)$menuId;
        } catch (\Throwable $e) {
            $this->error = 'Remove self menu failed.';
            $menu->rollback();
            return false;
        }
    }

    protected function createChildrenMenu(int $menuId, string $routeName)
    {
        $menu = new MenuService();
        $currentTime = date("Y-m-d H:i:s");
        $menu->startTrans();
        try {
            $initMenus = [
                ['parent_id' => $menuId, 'title' => 'add', 'path' => '/basic-list/api/' . $routeName . '/add', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
                ['parent_id' => $menuId, 'title' => 'edit', 'path' => '/basic-list/api/' . $routeName . '/:id', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
            ];
            $menu->saveAll($initMenus);
            $menu->commit();
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Create menu menu failed.';
            $menu->rollback();
            return false;
        }
    }

    protected function removeChildrenMenu(int $menuId)
    {
        $menu = new MenuService();
        $menu->startTrans();
        try {
            $menusData = $menu->where('parent_id', $menuId)->select();
            if (!$menusData->isEmpty()) {
                foreach ($menusData as $item) {
                    $item->force()->delete();
                }
            }
            $menu->commit();
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Remove menu menu failed.';
            $menu->rollback();
            return false;
        }
    }

    protected function getExistingFields(string $tableName)
    {
        $existingFields = [];
        $columnsQuery = Db::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tableName';");
        if ($columnsQuery) {
            $existingFields = extractValues($columnsQuery, 'COLUMN_NAME');
        }
        return $existingFields;
    }

    protected function fieldsHandler($existingFields, $currentFields, $data, $tableName)
    {
        // Get fields group by types
        $delete = array_diff($existingFields, $currentFields);
        $add = array_diff($currentFields, $existingFields);
        $change = array_intersect($currentFields, $existingFields);

        $statements = [];
        foreach ($data['fields'] as $field) {
            switch ($field['type']) {
                case 'longtext':
                    $type = 'LONGTEXT';
                    $typeAddon = '';
                    $default = 'DEFAULT \'\'';
                    break;
                case 'number':
                    $type = 'INT';
                    $typeAddon = ' UNSIGNED';
                    $default = 'DEFAULT 0';
                    break;
                case 'datetime':
                    $type = 'DATETIME';
                    $typeAddon = '';
                    break;
                case 'tag':
                case 'switch':
                    $type = 'TINYINT';
                    $typeAddon = '(1)';
                    $default = 'DEFAULT 1';
                    break;
                default:
                    $type = 'VARCHAR';
                    $typeAddon = '(255)';
                    $default = 'DEFAULT \'\'';
                    break;
            }

            if (in_array($field['name'], $add)) {
                $method = 'ADD';
                $statements[] = " $method `${field['name']}` $type$typeAddon NOT NULL $default";
            }

            if (in_array($field['name'], $change)) {
                $method = 'CHANGE';
                $statements[] = " $method `${field['name']}` `${field['name']}` $type$typeAddon NOT NULL $default";
            }
        }

        foreach ($delete as $field) {
            $method = 'DROP IF EXISTS';
            if (!in_array($field, Config::get('model.reserved_field'))) {
                $statements[] = " $method `$field`";
            }
        }

        $alterTableSql = 'ALTER TABLE `' . $tableName . '` ' . implode(',', $statements) . ';';

        Db::startTrans();
        try {
            Db::query($alterTableSql);
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->error = 'Change table structure failed.';
            return false;
        }
    }
}
