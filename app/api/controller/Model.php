<?php

declare(strict_types=1);

namespace app\api\controller;

use app\api\service\Model as ModelService;
use think\facade\Db;
use think\facade\Config;
use think\facade\Console;
use app\api\service\AuthRule as RuleService;
use app\api\service\Menu as MenuService;
use think\helper\Str;

class Model extends Common
{
    protected $model;

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
        $result = $this->model->saveAPI($this->request->only($this->model->getAllowSave()));

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

    public function delete()
    {
        $result = $this->model->deleteAPI($this->request->param('ids'), $this->request->param('type'));
        
        return $this->json(...$result);
    }

    public function design($id)
    {
        $result = $this->model->designAPI($id);

        return $this->json(...$result);
    }

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
        if (!empty($data)) {
            Db::startTrans();
            try {
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
            } catch (\Exception $e) {
                Db::rollback();
            }
        }
        return $this->error('Nothing to do.');
    }
}
