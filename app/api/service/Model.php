<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;
use think\facade\Config;
use think\facade\Lang;
use think\Exception;
use aspirantzhang\octopusModelCreator\ModelCreator;

class Model extends ModelLogic
{
    public function saveAPI($data, array $relationModel = [])
    {
        $tableName = $data['table_name'];
        $modelTitle = $data['model_title'];

        if (
            $this->isReservedTable($tableName) &&
            !$this->checkUniqueValues($data) &&
            $this->tableAlreadyExist($tableName)
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
            ModelCreator::file($tableName, $modelTitle, $this->getCurrentLanguage())->create();
            // create tables and record
            $modelData = ModelCreator::db($tableName, $modelTitle, $this->getCurrentLanguage())->createModel();
            // save ruleId and menuId to model table
            static::update(['rule_id' => $modelData['topRuleId'], 'menu_id' => $modelData['topMenuId']], ['id' => $this->getData('id')]);
            $this->commit();
            return $this->success(__('add successfully'));
        } catch (Exception $e) {
            $this->rollback();
            return $this->error($e->getMessage() ?: __('operation failed'));
        }
    }

    public function deleteAPI($ids = [], $type = 'delete')
    {
        if (isset($ids[0]) && $ids[0]) {
            $model = $this->withTrashed()->find($ids[0]);
            if ($model) {
                $tableName = $model->getAttr('table_name');
                $ruleId = $model->getAttr('rule_id');
                $menuId = $model->getAttr('menu_id');
                $model->startTrans();
                try {
                    $model->force()->delete();
                    // delete i18n table record
                    $this->deleteI18nRecord($ids[0]);
                    // remove model file
                    ModelCreator::file($tableName, '', $this->getCurrentLanguage())->remove();
                    // remove db record
                    ModelCreator::db($tableName, '', $this->getCurrentLanguage())->removeModel($ruleId, $menuId);
                    $model->commit();
                    return $this->success(__('delete successfully'));
                } catch (Exception $e) {
                    $model->rollback();
                    return $this->error($this->error ?: __('operation failed'));
                }
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
        $model = $this->where('id', $id)->find();
        if (!$model) {
            return $this->error(__('no target'));
        }

        $tableName = $model->getAttr('table_name');
        $modelData = $model->getAttr('data');
        if (
            $this->isReservedTable($tableName) &&
            $this->tableNotExist($tableName)
        ) {
            return $this->error($this->getError());
        }

        $model->startTrans();
        switch ($type) {
            case 'field':
                if (!empty($data) && !empty($data['fields'])) {
                    try {
                        $reservedFields = Config::get('reserved.reserved_field');
                        $allFields = extractValues($data['fields'], 'name');
                        $i18nTableFields = $this->extractTranslateFields($data['fields']);
                        $mainTableFields = array_diff($allFields, $reservedFields, $i18nTableFields);
                        // main table fields
                        ModelCreator::db($tableName, '', $this->getCurrentLanguage())->fieldsHandler($mainTableFields, $data['fields'], $reservedFields);
                        if (!empty($i18nTableFields)) {
                            // i18n table fields
                            ModelCreator::db($tableName . '_i18n', '', $this->getCurrentLanguage())->fieldsHandler($i18nTableFields, $data['fields'], $reservedFields);
                        }
                        // fields/validate translation etc.
                        ModelCreator::file($tableName, '', $this->getCurrentLanguage())->update($data['fields']);
                        // model table save
                        $model->data = $data;
                        $model->save();

                        $model->commit();
                        return $this->success(__('update successfully'));
                    } catch (Exception $e) {
                        $model->rollback();
                        return $this->error($e->getMessage());
                    }
                }
                break;
            case 'layout':
                if (!empty($data) && !empty($data['layout'])) {
                    try {
                        $modelData['layout'] = $data['layout'] ?? null;
                        $model->data = $modelData;
                        $model->save();
                        $model->commit();
                        return $this->success(__('update successfully'));
                    } catch (Exception $e) {
                        $model->rollback();
                        return $this->error($e->getMessage());
                    }
                }
                break;
            default:
                break;
        }
        
        return $this->error(__('no target'));
    }
}
