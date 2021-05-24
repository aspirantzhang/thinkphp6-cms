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
            return false;
        }
        return true;
    }

    protected function createModelFile(string $modelName)
    {
        try {
            Console::call('make:buildModel', [Str::studly($modelName), '--route=' . $modelName]);
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Create model file failed.';
            return false;
        }
    }

    protected function removeModelFile(string $modelName)
    {
        try {
            Console::call('make:removeModel', [Str::studly($modelName)]);
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

    protected function deleteI18nRecord($originalId)
    {
        try {
            Db::name('model_i18n')->where('original_id', $originalId)->delete();
        } catch (\Throwable $th) {
            $this->error = 'Remove i18n record failed.';
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
        return $rule[0]['data']['id'] ?: 0;
    }

    protected function removeRules(int $ruleId)
    {
        (new RuleService())->deleteAPI([$ruleId], 'deletePermanently');
    }

    protected function createChildrenRule(int $ruleId, string $tableTitle, string $modelName)
    {
        $currentTime = date("Y-m-d H:i:s");
        $childrenRules = [
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Home', 'rule_path' => 'api/' . $modelName . '/home', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Add', 'rule_path' => 'api/' . $modelName . '/add', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Save', 'rule_path' => 'api/' . $modelName . '/save', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Read', 'rule_path' => 'api/' . $modelName . '/read', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Update', 'rule_path' => 'api/' . $modelName . '/update', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Delete', 'rule_path' => 'api/' . $modelName . '/delete', 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $ruleId, 'rule_title' => $tableTitle . ' Restore', 'rule_path' => 'api/' . $modelName . '/restore', 'create_time' => $currentTime, 'update_time' => $currentTime],
        ];
        foreach ($childrenRules as $childrenRule) {
            (new RuleService())->saveAPI($childrenRule);
        }
    }

    protected function createSelfMenu(string $modelName, string $tableTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $menu = (new MenuService())->saveAPI([
            'parent_id' => 0,
            'menu_title' => $tableTitle . ' List',
            'icon' => 'icon-project',
            'path' => '/basic-list/api/' . $modelName,
            'create_time' => $currentTime,
            'update_time' => $currentTime,
        ]);
        return $menu[0]['data']['id'] ?: 0;
    }

    protected function removeMenus(int $menuId)
    {
        (new MenuService())->deleteAPI([$menuId], 'deletePermanently');
    }

    protected function deleteLangFile(string $modelName)
    {
        $languages = Config::get('lang.allow_lang_list');
        foreach ($languages as $lang) {
            @unlink(base_path() . 'api\lang\fields\\' . $lang . '\\' . $modelName . '.php');
        }
    }

    protected function createChildrenMenu(int $menuId, string $modelName, string $tableTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $childrenMenus = [
            ['parent_id' => $menuId, 'menu_title' => $tableTitle . ' Add', 'path' => '/basic-list/api/' . $modelName . '/add', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $menuId, 'menu_title' => $tableTitle . ' Edit', 'path' => '/basic-list/api/' . $modelName . '/:id', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
        ];
        foreach ($childrenMenus as $childrenMenu) {
            (new MenuService())->saveAPI($childrenMenu);
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

    protected function writeLangFile($fields, $modelName)
    {
        $data = '';
        foreach ($fields as $field) {
            if (strpos($field['name'], $modelName . '.') !== false) {
                $data = $data . "        '" . str_replace($modelName . '.', '', $field['name']) . "' => '" . $field['title'] . "',\n";
            }
        }
        // remove last ,\n
        $data = substr($data, 0, -2);
        $fileContent = <<<END
<?php

return [
    '$modelName' => [
$data
    ]
];

END;
        return file_put_contents(base_path() . 'api\lang\fields\\' . $this->getCurrentLanguage() . '\\' . $modelName . '.php', $fileContent);
    }
}
