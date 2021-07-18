<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;
use think\facade\Config;
use aspirantzhang\octopusModelCreator\ModelCreator;

class Model extends ModelLogic
{
    public function saveAPI($data, array $relationModel = [])
    {
        $modelName = $data['model_name'];
        $modelTitle = $data['model_title'];

        if (
            $this->isReservedTable($modelName) &&
            !$this->checkUniqueValues($data) &&
            $this->tableAlreadyExist($modelName)
        ) {
            return $this->error($this->getError());
        }

        $this->startTrans();
        try {
            // save model table data
            $this->allowField($this->getNoNeedToTranslateFields('save'))->save($data);
            // save i18n table data
            $this->saveI18nData($data, (int)$this->getData('id'), $this->getCurrentLanguage(), convertTime($data['create_time']));
            // create files
            ModelCreator::file($modelName, $modelTitle, $this->getCurrentLanguage())->create();
            // create tables and record
            $modelData = ModelCreator::db($modelName, $modelTitle, $this->getCurrentLanguage())->createModel();
            // save ruleId and menuId to model table
            static::update(['rule_id' => $modelData['topRuleId'], 'menu_id' => $modelData['topMenuId']], ['id' => $this->getData('id')]);
            $this->commit();
            return $this->success(__('add successfully'));
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($e->getMessage() ?: __('operation failed'));
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

                $modelName = $model->getAttr('model_name');
                $ruleId = $model->getAttr('rule_id');
                $menuId = $model->getAttr('menu_id');

                // remove model file
                ModelCreator::file($modelName, '', $this->getCurrentLanguage())->remove();
                // remove db record
                ModelCreator::db($modelName, '', $this->getCurrentLanguage())->removeModel($ruleId, $menuId);
                // remove I18n files
                $this->deleteLangFile($modelName);
                // remove allow fields config file
                $this->deleteAllowFieldsFile($modelName);
                
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
            $result = $result->toArray();
            $result['page']['title'] = __('model design');
            return $this->success('', $result);
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
        if ($this->tableNotExist($modelName)) {
            return $this->error($this->getError());
        }

        switch ($type) {
            case 'field':
                if (!empty($data) && !empty($data['fields'])) {
                    // Get current fields
                    $currentFields = extractValues($data['fields'], 'name');
                    // Get i18n fields
                    $i18nFields = $this->getTranslateFields($data['fields']);
                    if (!$i18nFields) {
                        return $this->error($this->getError());
                    }

                    // main table
                    $mainTableExist = $this->getExistingFields($modelName);
                    // Exclude reserved and i18n fields
                    $mainTableNew = array_diff($currentFields, Config::get('reserved.reserved_field'), $i18nFields);
                    $mainTableChangeResult = $this->fieldsHandler($mainTableExist, $mainTableNew, $data, $modelName);
                    if (!$mainTableChangeResult) {
                        return $this->error($this->getError());
                    }
                    // i18n table
                    $i18nTableExist = $this->getExistingFields($modelName . '_i18n');
                    $i18nTableChangeResult = $this->fieldsHandler($i18nTableExist, $i18nFields, $data, $modelName . '_i18n');
                    if (!$i18nTableChangeResult) {
                        return $this->error($this->getError());
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
                $model = $this->where('id', $id)->find();
                $modelData = $model->data;
                $modelData['layout'] = $data['layout'] ?? null;
                return $this->updateAPI($id, ['data' => $modelData]);
            default:
                break;
        }
        
        return $this->error(__('no target'));
    }
}
