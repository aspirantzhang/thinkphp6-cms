<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\Exception;
use aspirantzhang\octopusRevision\Revision;
use aspirantzhang\octopusRevision\RevisionAPI;

trait Update
{
    public function updateAPI(int $id, array $data, array $relationModel = [])
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($this->checkUniqueValues($data) === false) {
                return $this->error($this->getError());
            }
            $model->startTrans();
            try {
                (new RevisionAPI())->saveAPI('update (autosave)', $this->getTableName(), $id, $this->revisionTable);
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
            } catch (Exception $e) {
                $model->rollback();
                return $this->error($e->getMessage() ?: __('operation failed'));
            }
        } else {
            return $this->error(__('no target'));
        }
    }

    public function i18nUpdateAPI(int $id, array $data)
    {
        $originalRecord = $this->where('id', $id)->find();
        if ($originalRecord) {
            $currentTime = date("Y-m-d H:i:s");
            (new RevisionAPI())->saveAPI('i18n update (autosave)', $this->getTableName(), $id, $this->revisionTable);
            foreach ($data as $langCode => $fieldsData) {
                // validator check
                $modelValidator = '\app\api\validate\\' . $this->getModelName();
                $validate = new $modelValidator();
                $result = $validate->only($this->getAllowTranslate())->check($fieldsData);
                if (!$result) {
                    return $this->error($validate->getError());
                }
                // handle mutator
                $fieldsData = $this->handleMutator($fieldsData);
                // handle update
                try {
                    $this->updateI18nData($fieldsData, $id, $langCode, $currentTime);
                } catch (Exception $e) {
                    return $this->error($e->getMessage() ?: __('operation failed'));
                }
            }
            return $this->success(__('update successfully'));
        } else {
            return $this->error(__('no target'));
        }
    }
}
