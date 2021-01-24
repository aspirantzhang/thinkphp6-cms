<?php

declare(strict_types=1);

namespace app\backend\controller;

use app\backend\service\Model as ModelService;
use think\facade\Db;
use think\facade\Config;
use think\facade\Console;
use app\backend\service\AuthRule as RuleService;
use app\backend\service\Menu as MenuService;
use think\helper\Str;

class Model extends Common
{
    public function initialize()
    {
        $this->model = new ModelService();
        parent::initialize();
    }

    public function home()
    {
        $result = $this->model->paginatedListAPI($this->request->only($this->model->getAllowHome()));

        return $this->json(...$result);
    }

    public function add()
    {
        $result = $this->model->addAPI();

        return $this->json(...$result);
    }

    public function save()
    {
        $tableName = strtolower($this->request->param('table_name'));
        $routeName = strtolower($this->request->param('route_name'));
        $tableTitle = $this->request->param('title');
        $currentTime = date("Y-m-d H:i:s");

        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved table name.');
        }

        if ($this->existsTable($tableName)) {
            return $this->error('Table already exists.');
        }

        $result = $this->model->saveAPI($this->request->only($this->model->getAllowSave()));
        [ $httpBody ] = $result;

        if ($httpBody['success'] === true) {
            Db::startTrans();
            try {
                // Create Files
                Console::call('make:buildModel', [Str::studly($tableName), '--route=' . $routeName]);

                // Create Table
                Db::execute("CREATE TABLE `$tableName` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `create_time` DATETIME NOT NULL , `update_time` DATETIME NOT NULL , `delete_time` DATETIME NULL DEFAULT NULL , `status` TINYINT(1) NOT NULL DEFAULT '1' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;");

                // Add Rules
                $parentRule = RuleService::create([
                'parent_id' => 0,
                'name' => $tableTitle,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
                ]);
                $parentRuleId = $parentRule->id;
                $rule = new RuleService();
                $initRules = [
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Home', 'rule' => 'backend/' . $routeName . '/home', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Add', 'rule' => 'backend/' . $routeName . '/add', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Save', 'rule' => 'backend/' . $routeName . '/save', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Read', 'rule' => 'backend/' . $routeName . '/read', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Update', 'rule' => 'backend/' . $routeName . '/update', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Delete', 'rule' => 'backend/' . $routeName . '/delete', 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentRuleId, 'name' => $tableTitle . ' Restore', 'rule' => 'backend/' . $routeName . '/restore', 'create_time' => $currentTime, 'update_time' => $currentTime],
                ];
                $rule->saveAll($initRules);

                // Add Menus
                $parentMenu = MenuService::create([
                'parent_id' => 0,
                'name' => $routeName . '-list',
                'icon' => 'icon-project',
                'path' => '/basic-list/backend/' . $routeName,
                'create_time' => $currentTime,
                'update_time' => $currentTime,
                ]);
                $parentMenuId = $parentMenu->id;
                $menu = new MenuService();
                $initMenus = [
                    ['parent_id' => $parentMenuId, 'name' => 'add', 'path' => '/basic-list/backend/' . $routeName . '/add', 'hideInMenu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
                    ['parent_id' => $parentMenuId, 'name' => 'edit', 'path' => '/basic-list/backend/' . $routeName . '/:id', 'hideInMenu' => 1, 'create_time' => $currentTime, 'update_time' => $currentTime],
                ];
                $menu->saveAll($initMenus);
            } catch (\Exception $e) {
                Db::rollback();
            }
        }

        return $this->json(...$result);
    }

    public function read($id)
    {
        $result = $this->model->readAPI($id);

        return $this->json(...$result);
    }

    public function update($id)
    {
        $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

        return $this->json(...$result);
    }

    // TODO: Transaction delete table
    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        [ $httpBody ] = $result;

        if ($httpBody['success'] === true && isset($httpBody['data']) && count($httpBody['data']) === 1) {
            $tableTitle = $httpBody['data'][0]['title'];
            $tableName = $httpBody['data'][0]['table_name'];
            $routeName = $httpBody['data'][0]['route_name'];

            Console::call('make:removeModel', [Str::studly($tableName)]);

            // Drop Table
            Db::execute("DROP TABLE IF EXISTS `$tableName`");

            // Delete Parent Rule
            $parentRule = RuleService::where('name', $tableTitle)->find();
            $parentRuleId = $parentRule->id;
            $parentRule->force()->delete();
            // Delete Children Rule
            $childrenRule = new RuleService();
            $childrenRuleDataSet = $childrenRule->where('parent_id', $parentRuleId)->select();
            if (!$childrenRuleDataSet->isEmpty()) {
                foreach ($childrenRuleDataSet as $item) {
                    $item->force()->delete();
                }
            }

            // Delete Parent Menu
            $parentMenu = MenuService::where('name', $routeName . '-list')->find();
            $parentMenuId = $parentMenu->id;
            $parentMenu->force()->delete();
            // Delete Children Menu
            $childrenMenu = new MenuService();
            $childrenMenuDataSet = $childrenMenu->where('parent_id', $parentMenuId)->select();
            if (!$childrenMenuDataSet->isEmpty()) {
                foreach ($childrenMenuDataSet as $item) {
                    $item->force()->delete();
                }
            }
        }

        return $this->json(...$result);
    }

    public function restore()
    {
        $result = $this->model->restoreAPI($this->request->param('ids'));

        return $this->json(...$result);
    }

    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }

    // TODO: transaction
    public function designUpdate($id)
    {
        $tableName = ModelService::where('id', $id)->value('table_name');

        // Reserved model check
        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved model, operation not allowed.');
        }

        // Check table exists
        if (!$this->existsTable($tableName)) {
            return $this->error($this->error);
        }

        // Build fields sql statement.
        $data = $this->request->param('data');
        if ($data) {
            // Get all exist fields
            $existingFields = [];
            $columnsQuery = Db::query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$tableName';");
            if ($columnsQuery) {
                $existingFields = extractValues($columnsQuery, 'COLUMN_NAME');
            }
            // Get this fields
            $thisFields = extractValues($data['fields'], 'name');
            // Exclude reserved fields
            $thisFields = array_diff($thisFields, Config::get('model.reserved_field'));

            // Get fields group by types
            $delete = array_diff($existingFields, $thisFields);
            $add = array_diff($thisFields, $existingFields);
            $change = array_intersect($thisFields, $existingFields);

            $fieldSqlArray = [];
            foreach ($data['fields'] as $field) {
                $type = 'VARCHAR';
                $typeAddon = '(255)';
                $default = '';
                switch ($field['type']) {
                    case 'number':
                        $type = 'INT';
                        $typeAddon = ' UNSIGNED';
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
                    case 'longtext':
                        $type = 'LONGTEXT';
                        $typeAddon = '';
                        break;
                    default:
                        break;
                }

                if (in_array($field['name'], $add)) {
                    $method = 'ADD';
                    $fieldSqlArray[] = " $method `${field['name']}` $type$typeAddon NOT NULL $default";
                }

                if (in_array($field['name'], $change)) {
                    $method = 'CHANGE';
                    $fieldSqlArray[] = " $method `${field['name']}` `${field['name']}` $type$typeAddon NOT NULL $default";
                }
            }

            foreach ($delete as $field) {
                $method = 'DROP IF EXISTS';
                if (!in_array($field, Config::get('model.reserved_field'))) {
                    $fieldSqlArray[] = " $method `$field`";
                }
            }

            $alterTableSql = 'ALTER TABLE `' . $tableName . '` ' . implode(',', $fieldSqlArray) . '; ';

            Db::query($alterTableSql);

            $result = $this->model->updateAPI($id, $this->request->only($this->model->getAllowUpdate()));

            return $this->json(...$result);
        }
        return $this->error('Nothing to do.');
    }
}
