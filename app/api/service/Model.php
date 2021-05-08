<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;
use think\facade\Config;

class Model extends ModelLogic
{
    public function saveAPI($data, array $relationModel = [])
    {
        $tableName = strtolower($data['table_name']);
        $routeName = strtolower($data['route_name']);
        $tableTitle = (string)$data['title'];

        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved table name.');
        }

        if ($this->existsTable($tableName)) {
            return $this->error('Table already exists.');
        }

        if ($this->checkUniqueFields($data) === false) {
                return $this->error($this->error);
        }

        $this->startTrans();
        try {
            $this->error = 'Save failed.';
            // save basic data
            $this->allowField($this->getAllowSave())->save($data);
            
            // create files
            $this->createModelFile($tableName, $routeName);

            // Create table
            $this->createTable($tableName);

            // Add self rule
            $ruleId = $this->createSelfRule($tableTitle);

            // Add children rule
            $this->createChildrenRule($ruleId, $tableTitle, $tableName);

            // Add self menu
            $menuId = $this->createSelfMenu($routeName);

            // Add children menu
            $this->createChildrenMenu($menuId, $routeName);

            $this->commit();
            return $this->success('Add successfully.');
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error);
        }
    }

    public function designAPI($id)
    {
        $result = $this->field('data')->find($id);
        if ($result) {
            return $this->success('', $result->toArray());
        } else {
            return $this->error('Target not found.');
        }
    }
}
