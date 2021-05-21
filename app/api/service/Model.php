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
        $tableTitle = (string)$data['model_title'];

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
            // Save basic data
            $this->allowField($this->getNoNeedToTranslateFields('save'))->save($data);
            $this->saveI18nData($data, $this->getData('id'));
            
            // Create files
            $this->createModelFile($tableName, $routeName);

            // Create table
            $this->createTable($tableName);

            // Add self rule
            $ruleId = $this->createSelfRule($tableTitle);

            if ($ruleId) {
                // Add children rule
                $this->createChildrenRule((int)$ruleId, $tableTitle, $tableName);
            }

            // Add self menu
            $menuId = $this->createSelfMenu($routeName, $tableTitle);

            if ($menuId) {
                // Add children menu
                $this->createChildrenMenu((int)$menuId, $routeName, $tableTitle);
            }

            // store ruleId and menuId for facilitate deletion of model
            if ($ruleId && $menuId) {
                static::update(['rule_id' => $ruleId, 'menu_id' => $menuId], ['id' => $this->getData('id')]);
            }
            
            $this->commit();
            return $this->success('Add successfully.');
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error ?: 'Save failed.');
        }
    }

    public function deleteAPI($ids = [], $type = 'delete')
    {
        if (!empty($ids)) {
            $model = $this->withTrashed()->find($ids[0]);
            $model->startTrans();
            try {
                $model->force()->delete();

                $tableName = $model->table_name;
                $ruleId = $model->rule_id;
                $menuId = $model->menu_id;

                // Remove model file
                $this->removeModelFile($tableName);

                // Remove Table
                $this->removeTable($tableName);

                // Remove rules
                $this->removeRules($ruleId);

                // Remove menus
                $this->removeMenus($menuId);

                // Remove I18n files
                $this->removeI18n($tableName);

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

        if (!empty($data) && !empty($data['fields'])) {
            // Get all existing fields
            $existingFields = $this->getExistingFields($tableName);
            // Get current fields
            $currentFields = extractValues($data['fields'], 'name');
            // Exclude reserved fields
            $currentFields = array_diff($currentFields, Config::get('model.reserved_field'));
            // Handle table change
            $changeFieldInTable = $this->fieldsHandler($existingFields, $currentFields, $data, $tableName);

            if ($changeFieldInTable) {
                $updateDataField = $this->updateAPI($id, ['data' => $data]);
                if ($updateDataField[0]['success'] === true) {
                    // write to i18n file
                    $this->writeLangFile($data['fields'], $tableName);
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
