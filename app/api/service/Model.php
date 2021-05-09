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
            // Save basic data
            $this->allowField($this->getAllowSave())->save($data);
            
            // Create files
            $this->createModelFile($tableName, $routeName);

            // Create table
            $this->createTable($tableName);

            // Add self rule
            $ruleId = $this->createSelfRule($tableTitle);

            if ($ruleId) {
                // Add children rule
                $this->createChildrenRule($ruleId, $tableTitle, $tableName);
            }

            // Add self menu
            $menuId = $this->createSelfMenu($routeName);

            if ($menuId) {
                // Add children menu
                $this->createChildrenMenu($menuId, $routeName);
            }

            $this->commit();
            return $this->success('Add successfully.');
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error);
        }
    }

    public function deleteAPI($ids = [], $type = 'delete')
    {
        if (!empty($ids)) {
            $model = $this->withTrashed()->find($ids[0]);
            $model->startTrans();
            try {
                $model->force()->delete();

                $tableTitle = $model->title;
                $tableName = $model->table_name;
                $routeName = $model->route_name;

                // Remove model file
                $this->removeModelFile($tableName);

                // Remove Table
                $this->removeTable($tableName);

                // Remove self rule
                $ruleId = $this->removeSelfRule($tableTitle);

                if ($ruleId) {
                    // Remove children rules
                    $this->removeChildrenRule($ruleId);
                }

                // Remove self menu
                $menuId = $this->removeSelfMenu($routeName);

                if ($menuId) {
                    // Remove children menu
                    $this->removeChildrenMenu($menuId);
                }

                $model->commit();
                return $this->success('Delete successfully.');
            } catch (\Exception $e) {
                $model->rollback();
            }
        }
        return $this->error('Nothing to do.');
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

    public function designUpdateAPI($id, $data)
    {
        $tableName = $this->where('id', $id)->value('table_name');

        // Reserved model check
        if (in_array($tableName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved model, operation not allowed.');
        }

        // Check table exists
        if (!$this->existsTable($tableName)) {
            return $this->error($this->error);
        }

        if (!empty($data)) {
            // Get all existing fields
            $existingFields = $this->getExistingFields($tableName);
            // Get current fields
            $currentFields = extractValues($data['fields'], 'name');
            // Exclude reserved fields
            $currentFields = array_diff($currentFields, Config::get('model.reserved_field'));
            // Handle table change
            $result = $this->fieldsHandler($existingFields, $currentFields, $data, $tableName);

            if ($result) {
                $updateResult = $this->updateAPI($id, ['data' => $data]);
                if ($updateResult[0]['success'] === true) {
                    return $this->success('Update successfully.');
                }

                return $this->error('Update failed.');
            } else {
                return $this->error($this->error);
            }
        }
        return $this->error('Nothing to do.');
    }
}
