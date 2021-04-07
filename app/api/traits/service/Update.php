<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Update
{
    public function updateAPI($id, $data, array $relationModel = [])
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($this->checkUniqueFields($data) === false) {
                return $this->error($this->error);
            }
            $model->startTrans();
            try {
                $model->allowField($this->getAllowUpdate())->save($data);
                if ($relationModel) {
                    foreach ($relationModel as $relation) {
                        $model->$relation()->sync($data[$relation]);
                    }
                }
                $model->commit();

                return $this->success('Update successfully.');
            } catch (\Exception $e) {
                $model->rollback();

                return $this->error('Update failed.');
            }
        } else {
            return $this->error('Target not found.');
        }
    }
}
