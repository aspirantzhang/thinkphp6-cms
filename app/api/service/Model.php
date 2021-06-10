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
        $modelTitle = (string)$data['model_title'];

        if (in_array($modelName, Config::get('reserved.reserved_table'))) {
            return $this->error(__('reserved table name'));
        }

        if ($this->checkUniqueFields($data, $this->getTableName()) === false) {
            return $this->error($this->error);
        }

        if ($this->existsTable($modelName)) {
            return $this->error(__('table already exists', ['tableName' => $modelName]));
        }

        $this->startTrans();
        try {
            // Save basic data
            $this->allowField($this->getNoNeedToTranslateFields('save'))->save($data);
            $this->saveI18nData($data, $this->getData('id'));
            
            // Create files
            $this->createModelFile($modelName);

            if ($this->writeLayoutLangFile($modelName, $modelTitle) === false) {
                return $this->error(__('failed to write layout i18n file'));
            }

            // Create table
            $this->createTable($modelName);

            // Add self rule
            $ruleId = $this->createSelfRule($modelTitle);

            if ($ruleId) {
                // Add children rule
                $this->createChildrenRule((int)$ruleId, $modelTitle, $modelName);
            }

            // Add self menu
            $menuId = $this->createSelfMenu($modelName, $modelTitle);

            if ($menuId) {
                // Add children menu
                $this->createChildrenMenu((int)$menuId, $modelName, $modelTitle);
            }

            // store ruleId and menuId for facilitate deletion of model
            if ($ruleId && $menuId) {
                static::update(['rule_id' => $ruleId, 'menu_id' => $menuId], ['id' => $this->getData('id')]);
            }
            
            $this->commit();
            return $this->success(__('add successfully'));
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error ?: __('operation failed'));
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
                return $this->success(__('delete successfully'));
            } catch (\Exception $e) {
                $model->rollback();
                return $this->error($this->error ?: __('operation failed'));
            }
        }
        return $this->error(__('no target'));
    }

    public function designAPI($id)
    {
        $result = $this->field('data')->find($id);
        if ($result) {
            return $this->success('', $result->toArray());
        } else {
            return $this->error(__('no target'));
        }
    }

    public function designUpdateAPI($id, $type, $data)
    {
        $modelName = $this->where('id', $id)->value('model_name');

        if (!$modelName) {
            return $this->error(__('no target'));
        }
        // Reserved model check
        if (in_array($modelName, Config::get('reserved.reserved_table'))) {
            return $this->error(__('reserved model not allowed'));
        }
        // Check table exists
        if (!$this->existsTable($modelName)) {
            return $this->error(__('table not exist', ['tableName' => $modelName]));
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
                    $mainTableNew = array_diff($currentFields, Config::get('reserved.reserved_field'), $i18nFields);
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
                            return $this->error(__('failed to write field i18n file'));
                        }
                        // write validate file
                        $validateRule = $this->createValidateRules($data['fields'], $modelName);
                        $validateMsg = $this->createMessages($validateRule, $modelName);
                        $validateScene = $this->createScene($data['fields']);
                        if ($this->writeValidateFile($modelName, $validateRule, $validateMsg, $validateScene) === false) {
                            return $this->error(__('failed to write validate file'));
                        }
                        // write validator i18n file
                        if ($this->writeValidateI18nFile($modelName, $validateMsg) === false) {
                            return $this->error(__('failed to write validate i18n file'));
                        }
                        // write allow fields file
                        if ($this->writeAllowConfigFile($modelName, $data['fields']) === false) {
                            return $this->error(__('failed to write allow fields config file'));
                        }

                        return $this->success(__('update successfully'));
                    }
                    return $this->error(__('operation failed'));
                }
                break;

            case 'layout':
                return $this->updateAPI($id, ['data' => $data]);
            
            default:
                break;
        }
        
        return $this->error(__('no target'));
    }
}
