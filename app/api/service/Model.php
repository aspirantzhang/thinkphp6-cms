<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;
use think\facade\Config;

class Model extends ModelLogic
{
    public function saveAPI($data, array $relationModel = [])
    {
        $modelName = strtolower($data['model_name']);
        $tableTitle = (string)$data['model_title'];

        if (in_array($modelName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved table name.');
        }

        if ($this->existsTable($modelName)) {
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
            $this->createModelFile($modelName);

            // Create table
            $this->createTable($modelName);

            // Add self rule
            $ruleId = $this->createSelfRule($tableTitle);

            if ($ruleId) {
                // Add children rule
                $this->createChildrenRule((int)$ruleId, $tableTitle, $modelName);
            }

            // Add self menu
            $menuId = $this->createSelfMenu($modelName, $tableTitle);

            if ($menuId) {
                // Add children menu
                $this->createChildrenMenu((int)$menuId, $modelName, $tableTitle);
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

                // delete i18n table record
                $this->deleteI18nRecord($ids[0]);

                $modelName = $model->model_name;
                $ruleId = $model->rule_id;
                $menuId = $model->menu_id;

                // Remove model file
                $this->removeModelFile($modelName);

                // Remove Table
                $this->removeTable($modelName);

                // Remove rules
                $this->removeRules($ruleId);

                // Remove menus
                $this->removeMenus($menuId);

                // Remove I18n files
                $this->deleteLangFile($modelName);

                // Remove allow fields config file
                $this->deleteAllowFieldsFile($modelName);

                // Remove validate file
                $this->deleteValidateFile($modelName);
                
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

    public function designUpdateAPI($id, $type, $data)
    {
        $modelName = $this->where('id', $id)->value('model_name');

        if (!$modelName) {
            return $this->error('Target not found.');
        }
        // Reserved model check
        if (in_array($modelName, Config::get('model.reserved_table'))) {
            return $this->error('Reserved model, operation not allowed.');
        }
        // Check table exists
        if (!$this->existsTable($modelName)) {
            return $this->error('Model table not exist.');
        }

        switch ($type) {
            case 'field':
                if (!empty($data) && !empty($data['fields'])) {
                    // Get current fields
                    $currentFields = extractValues($data['fields'], 'name');
                    // Get i18n fields
                    $i18nFields = $this->getTranslateFields($data['fields']);

                    // main table
                    $mainTableExist = $this->getExistingFields($modelName);
                    // Exclude reserved and i18n fields
                    $mainTableNew = array_diff($currentFields, Config::get('model.reserved_field'), $i18nFields);
                    $mainTableChangeResult = $this->fieldsHandler($mainTableExist, $mainTableNew, $data, $modelName);
                    if (!$mainTableChangeResult) {
                        return $this->error($this->error);
                    }
                    // i18n table
                    $i18nTableExist = $this->getExistingFields($modelName . '_i18n');
                    $i18nTableChangeResult = $this->fieldsHandler($i18nTableExist, $i18nFields, $data, $modelName . '_i18n');
                    if (!$i18nTableChangeResult) {
                        return $this->error($this->error);
                    }

                    $updateDataField = $this->updateAPI($id, ['data' => $data]);
                    if ($updateDataField[0]['success'] === true) {
                        // write to i18n file
                        if ($this->writeFieldLangFile($data['fields'], $modelName) === false) {
                            return $this->error('Write field i18n file failed.');
                        }
                        // write validate file
                        $validateRule = $this->createValidateRules($data['fields'], $modelName);
                        $validateMsg = $this->createMessages($validateRule, $modelName);
                        $validateScene = $this->createScene($data['fields']);
                        if ($this->writeValidateFile($modelName, $validateRule, $validateMsg, $validateScene) === false) {
                            return $this->error('Write validate file failed.');
                        }
                        // write validator i18n file
                        if ($this->writeValidateI18nFile($modelName, $validateMsg) === false) {
                            return $this->error('Write validate i18n file failed.');
                        }
                        // write allow fields file
                        if ($this->writeAllowConfigFile($modelName, $data['fields']) === false) {
                            return $this->error('Write allow fields file failed.');
                        }

                        return $this->success('Update successfully.');
                    }
                    return $this->error('Update failed.');
                }
                break;

            case 'layout':
                return $this->updateAPI($id, ['data' => $data]);
            
            default:
                break;
        }
        
        return $this->error('Nothing to do.');
    }
}
