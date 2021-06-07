<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Db;

trait Update
{
    protected function updateI18nData($rawData, $originalId)
    {
        $filteredData = array_intersect_key($rawData, array_flip($this->getAllowTranslate()));

        try {
            Db::name($this->getLangTableName())
                ->where('original_id', $originalId)
                ->where('lang_code', $this->getCurrentLanguage())
                ->update($filteredData);
            return true;
        } catch (\Throwable $e) {
            $this->error = 'Write data to i18n table failed.';
            return false;
        }
    }

    public function updateAPI($id, $data, array $relationModel = [])
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($model->checkUniqueFields($data, $this->getTableName()) === false) {
                return $this->error($this->error);
            }
            $model->startTrans();
            try {
                $model->allowField($this->getNoNeedToTranslateFields('update'))->save($data);
                $this->updateI18nData($data, $id);
                if ($relationModel) {
                    foreach ($relationModel as $relation) {
                        if (isset($data[$relation])) {
                            $model->$relation()->sync($data[$relation]);
                        }
                    }
                }
                $model->commit();

                return $this->success('Update successfully.');
            } catch (\Exception $e) {
                $model->rollback();

                return $this->error($this->error ?: 'Update failed.');
            }
        } else {
            return $this->error('Target not found.');
        }
    }
}
