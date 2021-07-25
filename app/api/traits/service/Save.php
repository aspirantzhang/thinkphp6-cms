<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Save
{
    public function saveAPI(array $data, array $relationModel = [])
    {
        if ($this->checkUniqueValues($data) === false) {
            return $this->error($this->getError());
        }
        $this->startTrans();
        try {
            $this->allowField($this->getNoNeedToTranslateFields('save'))->save($data);
            $id = (int)$this->getAttr('id');
            $this->saveI18nData($data, $id, $this->getCurrentLanguage(), convertTime($data['create_time']));
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
            return $this->error($e->getMessage() ?: __('operation failed'));
        }
    }
}
