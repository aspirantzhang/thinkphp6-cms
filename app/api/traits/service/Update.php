<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Db;

trait Update
{
    public function updateAPI($id, $data, array $relationModel = [])
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($model->checkUniqueFields($data, $this->getTableName()) === false) {
                return $this->error($this->getError());
            }
            $model->startTrans();
            try {
                $model->allowField($this->getNoNeedToTranslateFields('update'))->save($data);
                $this->updateI18nData($data, $id, $this->getCurrentLanguage());
                if ($relationModel) {
                    foreach ($relationModel as $relation) {
                        if (isset($data[$relation])) {
                            $model->$relation()->sync($data[$relation]);
                        }
                    }
                }
                $model->commit();

                return $this->success(__('update successfully'));
            } catch (\Exception $e) {
                $model->rollback();

                return $this->error($this->error ?: __('operation failed'));
            }
        } else {
            return $this->error(__('no target'));
        }
    }
}
