<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Db;

trait Save
{
    protected function saveI18nData($rawData, $originalId)
    {
        $filteredData = array_intersect_key($rawData, array_flip($this->getAllowTranslate()));
        $data = array_merge($filteredData, [
            'original_id' => $originalId,
            'lang_code' => $this->getCurrentLanguage()
        ]);
        try {
            Db::name($this->getLangTableName())->save($data);
            return true;
        } catch (\Throwable $e) {
            $this->error = __('failed to store i18n data');
            return false;
        }
    }

    public function saveAPI($data, array $relationModel = [])
    {
        if ($this->checkUniqueFields($data, $this->getTableName()) === false) {
            return $this->error($this->getError());
        }
        $this->startTrans();
        try {
            $this->allowField($this->getNoNeedToTranslateFields('save'))->save($data);
            $id = $this->getData('id');
            $this->saveI18nData($data, $id);
            if (!empty($relationModel)) {
                foreach ($relationModel as $relation) {
                    $data[$relation] = $data[$relation] ?? [];
                    $this->$relation()->sync($data[$relation]);
                }
            }
            $this->commit();
            return $this->success(__('add successfully'), ['id' => $id]);
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error ?: __('operation failed'));
        }
    }
}
