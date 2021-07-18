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

    protected function createTable(string $tableName)
    {
        try {
            Db::execute("CREATE TABLE `$tableName` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `create_time` DATETIME NOT NULL , `update_time` DATETIME NOT NULL , `delete_time` DATETIME NULL DEFAULT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $i18nTable = $tableName . '_i18n';
            Db::execute("CREATE TABLE `$i18nTable` ( `_id` int unsigned NOT NULL AUTO_INCREMENT , `original_id` int unsigned NOT NULL , `lang_code` char(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '', `translate_time` DATETIME NOT NULL, PRIMARY KEY (`_id`), UNIQUE KEY `original_id` (`original_id`,`lang_code`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");
            return true;
        } catch (\Throwable $e) {
            $this->error = __('create table failed', ['tableName' => $tableName]);
            return false;
        }
    }

    protected function deleteI18nRecord($originalId)
    {
        try {
            Db::name('model_i18n')->where('original_id', $originalId)->delete();
        } catch (\Throwable $th) {
            $this->error = __('remove i18n record failed');
        }
    }

    protected function createSelfRule(string $modelTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $rule = (new RuleService())->saveAPI([
            'parent_id' => 0,
            'rule_title' => $modelTitle,
            'create_time' => $currentTime,
            'update_time' => $currentTime,
        ]);
        return $rule[0]['data']['id'] ?: 0;
    }

    protected function addRulesToAdminGroup(array $newRuleIds)
    {
        $adminGroup = GroupService::where('id', 1)->with(['rules'])->find();
        $rulesArray = $adminGroup->toArray()['rules'];
        $existingRuleIds = extractValues($rulesArray);

        $result = (new GroupService())->updateAPI(1, [
            'rules' => [...$existingRuleIds, ...$newRuleIds]
        ], ['rules']);

        if ($result[0]['success'] === false) {
            $this->error = _('failed to add rules to AdminGroup');
            return false;
        }
        return true;
    }

    protected function createSelfMenu(string $modelName, string $modelTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $menu = (new MenuService())->saveAPI([
            'parent_id' => 0,
            'menu_title' => $modelTitle . Lang::get('list'),
            'icon' => 'icon-project',
            'path' => '/basic-list/api/' . $modelName,
            'create_time' => $currentTime,
            'update_time' => $currentTime,
        ]);
        return $menu[0]['data']['id'] ?: 0;
    }

    protected function createChildrenMenu(int $menuId, string $modelName, string $modelTitle)
    {
        $currentTime = date("Y-m-d H:i:s");
        $childrenMenus = [
            ['parent_id' => $menuId, 'menu_title' => $modelTitle . Lang::get('add'), 'path' => '/basic-list/api/' . $modelName . '/add', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
            ['parent_id' => $menuId, 'menu_title' => $modelTitle . Lang::get('edit'), 'path' => '/basic-list/api/' . $modelName . '/:id', 'hide_in_menu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
        ];
        foreach ($childrenMenus as $childrenMenu) {
            (new MenuService())->saveAPI($childrenMenu);
        }
    }

    protected function deleteLangFile(string $modelName)
    {
        $languages = Config::get('lang.allow_lang_list');
        foreach ($languages as $lang) {
            @unlink(base_path() . 'api\lang\fields\\' . $lang . '\\' . $modelName . '.php');
            @unlink(base_path() . 'api\lang\validator\\' . $lang . '\\' . $modelName . '.php');
        }
    }

    protected function deleteAllowFieldsFile(string $modelName)
    {
        @unlink(root_path() . 'config\api\allowFields\\' . Str::studly($modelName) . '.php');
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
            if (!in_array($field, Config::get('reserved.reserved_field'))) {
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
            $this->error = __('change table structure failed', ['tableName' => $tableName]);
            return false;
        }
    }

    protected function getTranslateFields($fields)
    {
        $result = [];
        foreach ($fields as $field) {
            if (($field['type'] === 'input' || $field['type'] === 'textarea') && $field['allowTranslate'] ?? false) {
                if (isset($field['settings']['display']) && in_array('editDisabled', $field['settings']['display'])) {
                    $this->error = __('edit disabled fields cannot set as translate', ['fieldName' => $field['name']]);
                    return false;
                }
                array_push($result, $field['name']);
            }
        }
        return $result;
    }

    protected function writeFieldLangFile($fields, $modelName)
    {
        $data = '';
        foreach ($fields as $field) {
            $data = $data . "        '" . $field['name'] . "' => '" . $field['title'] . "',\n";
        }
        $data = substr($data, 0, -1);
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

    /**
     * Create validate rules (+field prefix)
     * @param array $fields
     * @param string $modelName
     * @return string[]
     */
    protected function createValidateRules($fields, $modelName)
    {
        $rules = [
            'id' => 'require|number',
            'ids' => 'require|numberArray',
            'status' => 'numberTag',
            'page' => 'number',
            'per_page' => 'number',
            'create_time' => 'require|dateTimeRange',
        ];

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $ruleString = '';
            if (!empty($field['settings']['validate'])) {
                foreach ($field['settings']['validate'] as $validateName) {
                    switch ($validateName) {
                        case 'length':
                            $min = $field['settings']['options']['length']['min'] ?? 0;
                            $max = $field['settings']['options']['length']['max'] ?? 32;
                            $ruleString .= $validateName . ':' . (int)$min . ',' . (int)$max . '|';
                            break;
                        default:
                            $ruleString .= $validateName . '|';
                            break;
                    }
                }
                $ruleString = substr($ruleString, 0, -1);
                // +prefix
                $ruleName = $modelName . '@' . $fieldName;
                $rules[$ruleName] = $ruleString;
            }
        }
        return $rules;
    }

    protected function createMessages($rules, $modelName)
    {
        $result = [];
        foreach ($rules as $name => $rule) {
            $keyFieldName = strtr($name, [$modelName . '@' => '']);
            if (strpos($rule, '|')) {
                $ruleArr = explode('|', $rule);
                foreach ($ruleArr as $subRule) {
                    $result[$keyFieldName . '.' . $subRule] = $name . '#' . $subRule;
                }
            } else {
                $result[$keyFieldName . '.' . $rule] = $name . '#' . $rule;
            }
        }
        return $result;
    }

    protected function createScene($fields)
    {
        $scene = [
            'save' => ['create_time', 'status'],
            'update' => ['id', 'create_time', 'status'],
            'read' => ['id'],
            'delete' => ['ids'],
            'restore' => ['ids'],
            'i18n' => ['id'],
            'i18n_update' => ['id'],
            'add' => [''],
            'home' => [],
            'homeExclude' => []
        ];
        foreach ($fields as $field) {
            if (isset($field['settings']['validate']) && !empty($field['settings']['validate'])) {
                // home
                if ($field['allowHome'] ?? false) {
                    array_push($scene['home'], $field['name']);
                    array_push($scene['homeExclude'], $field['name']);
                }
                // save
                if ($field['allowSave'] ?? false) {
                    array_push($scene['save'], $field['name']);
                }
                // update
                if ($field['allowUpdate'] ?? false) {
                    array_push($scene['update'], $field['name']);
                }
            }
        }
        return $scene;
    }

    protected function writeValidateFile($modelName, $rules, $messages, $scenes)
    {
        $modelNameUpper = Str::studly($modelName);
        $filename = base_path() . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'validate' . DIRECTORY_SEPARATOR . $modelNameUpper . '.php';
        $stubName = base_path() . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'validate' . DIRECTORY_SEPARATOR . '_validate.stub';

        $ruleText = '';
        foreach ($rules as $ruleKey => $ruleValue) {
            $ruleText .= "        '" . strtr($ruleKey, [$modelName . '@' => '']) . "' => '" . $ruleValue . "',\n";
        }
        $ruleText = substr($ruleText, 0, -1);

        $messageText = '';
        foreach ($messages as $msgKey => $msgValue) {
            if (strpos($msgKey, ':')) {
                $msgKey = substr($msgKey, 0, strpos($msgKey, ':'));
            }
            $messageText .= "        '" . $msgKey . "' => '" . $msgValue . "',\n";
        }
        $messageText = substr($messageText, 0, -1);

        $sceneSave = $scenes['save'] ? '\'' . implode('\', \'', $scenes['save']) . '\'' : '';
        $sceneUpdate = $scenes['update'] ? '\'' . implode('\', \'', $scenes['update']) . '\'' : '';
        $sceneHome = $scenes['home'] ? '\'' . implode('\', \'', $scenes['home']) . '\'' : '';

        $sceneHomeExclude = '';
        foreach ($scenes['homeExclude'] as $exclude) {
            $sceneHomeExclude .= "\n" . '            ->remove(\'' . $exclude . '\', \'require\')';
        }

        $content = file_get_contents($stubName);
        $content = str_replace([
            '{%modelNameUpper%}',
            '{%rule%}',
            '{%message%}',
            '{%sceneSave%}',
            '{%sceneUpdate%}',
            '{%sceneHome%}',
            '{%sceneHomeExclude%}',
        ], [
            $modelNameUpper,
            $ruleText,
            $messageText,
            $sceneSave,
            $sceneUpdate,
            $sceneHome,
            $sceneHomeExclude,
        ], $content);
        return file_put_contents($filename, $content);
    }

    private function getValidateText($langKey)
    {
        $fieldName = substr($langKey, 0, strpos($langKey, '#'));
        $fieldName = strtr($fieldName, ['@' => '.']);
        $ruleName = substr($langKey, strpos($langKey, '#') + 1);
        $option = '';
        if (strpos($ruleName, ':')) {
            $ruleName = substr($ruleName, 0, strpos($ruleName, ':'));
            $option = substr($langKey, strpos($langKey, ':') + 1);
            if ($option) {
                $option = strtr($option, [',' => ' - ']);
            }
        }
        return Lang::get('validate.' . $ruleName, ['field' => Lang::get($fieldName), 'option' => $option]);
    }

    protected function writeValidateI18nFile($modelName, $messages)
    {
        if (file_exists(base_path() . 'api/lang/fields/' . Lang::getLangSet() . '/' . $modelName . '.php')) {
            Lang::load(base_path() . 'api/lang/fields/' . Lang::getLangSet() . '/' . $modelName . '.php');
        }
        $exclude = ['id.require', 'id.number', 'ids.require', 'ids.numberArray', 'status.numberTag', 'page.number', 'per_page.number', 'create_time.require', 'create_time.dateTimeRange'];
        $msgs = array_diff_key($messages, array_flip($exclude));

        $content = '';
        foreach ($msgs as $langKey) {
            $content .= '    \'' . $langKey . '\' => \'' . $this->getValidateText($langKey) . "',\n";
        }
        $content = substr($content, 0, -1);
        $content = <<<END
<?php

return [
$content
];

END;

        $filename = base_path() . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'lang' . DIRECTORY_SEPARATOR . 'validator' . DIRECTORY_SEPARATOR . Lang::getLangSet() . DIRECTORY_SEPARATOR . $modelName . '.php';
        return file_put_contents($filename, $content);
    }

    protected function writeAllowConfigFile($modelName, $fields)
    {
        $modelNameUpper = Str::studly($modelName);
        $filename = root_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'allowFields' . DIRECTORY_SEPARATOR . $modelNameUpper . '.php';
        $stubName = root_path() . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'allowFields' . DIRECTORY_SEPARATOR . '_config.stub';

        $allowHome = [];
        $allowRead = [];
        $allowSave = [];
        $allowUpdate = [];
        $allowTranslate = [];
        
        foreach ($fields as $field) {
            // home
            if ($field['allowHome'] ?? false) {
                array_push($allowHome, $field['name']);
            }
            // read
            if ($field['allowRead'] ?? false) {
                array_push($allowRead, $field['name']);
            }
            // save
            if ($field['allowSave'] ?? false) {
                array_push($allowSave, $field['name']);
            }
            // update
            if ($field['allowUpdate'] ?? false) {
                array_push($allowUpdate, $field['name']);
            }
            // translate
            if ($field['allowTranslate'] ?? false) {
                array_push($allowTranslate, $field['name']);
            }
        }

        $allowHomeText = $allowHome ? '\'' . implode('\', \'', $allowHome) . '\'' : '';
        $allowReadText = $allowRead ? '\'' . implode('\', \'', $allowRead) . '\'' : '';
        $allowSaveText = $allowSave ? '\'' . implode('\', \'', $allowSave) . '\'' : '';
        $allowUpdateText = $allowUpdate ? '\'' . implode('\', \'', $allowUpdate) . '\'' : '';
        $allowTranslateText = $allowTranslate ? '\'' . implode('\', \'', $allowTranslate) . '\'' : '';


        $content = file_get_contents($stubName);
        $content = str_replace([
            '{%allowHome%}',
            '{%allowRead%}',
            '{%allowSave%}',
            '{%allowUpdate%}',
            '{%allowTranslate%}',
        ], [
            $allowHomeText,
            $allowReadText,
            $allowSaveText,
            $allowUpdateText,
            $allowTranslateText,
        ], $content);
        return file_put_contents($filename, $content);
    }
}
