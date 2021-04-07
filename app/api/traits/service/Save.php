<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Save
{
    public function saveAPI($data, array $relationModel = [])
    {
        if ($this->checkUniqueFields($data) === false) {
            return $this->error($this->error);
        }
        $this->startTrans();
        try {
            $this->allowField($this->getAllowSave())->save($data);
            if (!empty($relationModel)) {
                foreach ($relationModel as $relation) {
                    $data[$relation] = $data[$relation] ?? [];
                    $this->$relation()->sync($data[$relation]);
                }
            }
            $this->commit();
            return $this->success('Add successfully.');
        } catch (\Exception $e) {
            $this->rollback();
            return $this->error('Save failed.');
        }
    }
}
