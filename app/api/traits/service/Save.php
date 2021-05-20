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
            $this->error = 'Write data to i18n table failed.';
            return false;
        }
    }

    public function saveAPI($data, array $relationModel = [])
    {
        if ($this->checkUniqueFields($data) === false) {
            return $this->error($this->error);
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
            return $this->success('Add successfully.', ['id' => $id]);
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error($this->error ?: 'Save failed.');
        }
    }
}
