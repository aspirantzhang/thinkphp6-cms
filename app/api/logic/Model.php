<?php

declare(strict_types=1);

namespace app\api\logic;

use app\api\view\Model as ModelView;
use app\api\service\AuthRule as RuleService;
use app\api\service\Menu as MenuService;
use app\api\service\AuthGroup as GroupService;
use think\facade\Db;
use think\facade\Console;
use think\facade\Config;
use think\facade\Lang;
use think\helper\Str;

class Model extends ModelView
{
    protected function isReservedTable(string $tableName): bool
    {
        if (in_array($tableName, Config::get('reserved.reserved_table'))) {
            $this->error = __('reserved table name');
            return true;
        }
        return false;
    }
    
    protected function tableExists(string $tableName): bool
    {
        try {
            Db::query("select 1 from `$tableName` LIMIT 1");
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    protected function tableAlreadyExist(string $tableName): bool
    {
        if ($this->tableExists($tableName)) {
            $this->error = __('table already exists', ['tableName' => $tableName]);
            return true;
        }
        return false;
    }

    public function tableNotExist(string $tableName): bool
    {
        if (!$this->tableExists($tableName)) {
            $this->error = __('table not exist', ['tableName' => $tableName]);
            return true;
        }
        return false;
    }

    protected function deleteI18nRecord($originalId)
    {
        try {
            Db::name('model_i18n')->where('original_id', $originalId)->delete();
        } catch (\Throwable $th) {
            $this->error = __('remove i18n record failed');
        }
    }

    protected function deleteLangFile(string $tableName)
    {
        $languages = Config::get('lang.allow_lang_list');
        foreach ($languages as $lang) {
            @unlink(base_path() . 'api\lang\fields\\' . $lang . '\\' . $tableName . '.php');
            @unlink(base_path() . 'api\lang\validator\\' . $lang . '\\' . $tableName . '.php');
        }
    }

    protected function deleteAllowFieldsFile(string $tableName)
    {
        @unlink(root_path() . 'config\api\allowFields\\' . Str::studly($tableName) . '.php');
    }

    protected function getExistingFields(string $tableName)
    {
        $existingFields = [];
        $columnsQuery = Db::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :tableName;", ['tableName' => $tableName]);
        if ($columnsQuery) {
            $existingFields = extractValues($columnsQuery, 'COLUMN_NAME');
        }
        return $existingFields;
    }

    protected function fieldsHandler(string $tableName, array $processedFields, array $fieldsData, array $reservedFields)
    {
        $existingFields = $this->getExistingFields($tableName);
        // group by types
        $delete = array_diff($existingFields, $processedFields);
        $add = array_diff($processedFields, $existingFields);
        $change = array_intersect($processedFields, $existingFields);

        $statements = [];
        foreach ($fieldsData['fields'] as $field) {
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
            if (!in_array($field, $reservedFields)) {
                $statements[] = " $method `$field`";
            }
        }

        $alterTableSql = 'ALTER TABLE `' . $tableName . '` ' . implode(',', $statements) . ';';

        try {
            Db::query($alterTableSql);
        } catch (\Exception $e) {
            throw new \Exception(__('change table structure failed', ['tableName' => $tableName]));
        }
    }

    protected function extractTranslateFields($allFields)
    {
        $result = [];
        foreach ($allFields as $field) {
            // only 'input' and 'textarea' can be translated
            if (
                isset($field['type']) &&
                ($field['type'] === 'input' || $field['type'] === 'textarea') &&
                $field['allowTranslate'] ?? false
            ) {
                // cannot be marked as 'editDisabled' and 'translate' ATST
                if (
                    isset($field['settings']['display']) &&
                    in_array('editDisabled', $field['settings']['display'])
                ) {
                    throw new \Exception(__('edit disabled fields cannot set as translate', ['fieldName' => $field['name']]));
                }
                array_push($result, $field['name']);
            }
        }
        return $result;
    }
}
