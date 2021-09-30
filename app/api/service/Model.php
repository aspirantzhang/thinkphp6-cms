<?php

declare(strict_types=1);

namespace app\api\service;

use app\api\logic\Model as ModelLogic;
use think\facade\Config;
use think\Exception;
use aspirantzhang\octopusModelCreator\ModelCreator;

class Model extends ModelLogic
{
    public function saveAPI($data, array $relationModel = [])
    {
        $data = $this->handleDataFilter($data);
        $tableName = $data['table_name'];
        $modelTitle = $data['model_title'];

        if (
            $this->isReservedTable($tableName) &&
            !$this->checkUniqueValue($data) &&
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
            ModelCreator::file($tableName, $modelTitle)->create();
            // create tables and record
            $modelData = ModelCreator::db($tableName, $modelTitle)->create();
            // save ruleId and menuId to model table
            static::update(['rule_id' => $modelData['topRuleId'], 'menu_id' => $modelData['topMenuId']], ['id' => $this->getData('id')]);
            $this->commit();
            return $this->success(__('add successfully'));
        } catch (Exception $e) {
            $this->rollback();
            return $this->error($e->getMessage() ?: __('operation failed'));
        }
    }

    public function deleteAPI(array $ids = [], string $type = 'delete')
    {
        if (isset($ids[0]) && $ids[0]) {
            $model = $this->withTrashed()->find((int)$ids[0]);
            if ($model) {
                $tableName = $model->getAttr('table_name');
                $ruleId = $model->getAttr('rule_id');
                $menuId = $model->getAttr('menu_id');
                $model->startTrans();
                try {
                    $model->force()->delete();
                    // delete i18n table record
                    $this->deleteI18nData((int)$ids[0]);
                    ModelCreator::file($tableName)->remove();
                    ModelCreator::db($tableName)->remove($ruleId, $menuId);
                    $model->commit();
                    return $this->success(__('delete successfully'));
                } catch (Exception $e) {
                    $model->rollback();
                    return $this->error($e->getMessage() ?: __('operation failed'));
                }
            }
        }
        return $this->error(__('no target'));
    }

    public function designAPI(int $id)
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

    public function designUpdateAPI(int $id, string $type, array $data)
    {
        $model = $this->withI18n($this)->where('id', $id)->find();
        if (!$model) {
            return $this->error(__('no target'));
        }

        $tableName = $model->getAttr('table_name');
        $modelTitle = $model->getAttr('model_title');
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
                if (!empty($data) && !empty($data['data'])) {
                    try {
                        $reservedFields = Config::get('reserved.reserved_field');
                        $allFields = extractValues($data['data'], 'name');
                        $i18nTableFields = $this->extractTranslateFields($data['data']);
                        $mainTableFields = array_diff($allFields, $reservedFields, $i18nTableFields);

                        ModelCreator::db($tableName, $modelTitle)->update($data['data'], $mainTableFields, $reservedFields, $i18nTableFields);
                        ModelCreator::file($tableName, $modelTitle)->update($data['data'], $data['options']);
                        // model table save
                        $modelData['fields'] = $data;
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
