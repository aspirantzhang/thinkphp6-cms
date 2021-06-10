<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Read
{
    public function readAPI($id, $relationModel = [])
    {
        $relationArray = [];

        if (!empty($relationModel)) {
            foreach ($relationModel as $relation) {
                $relationArray[$relation] = function ($query) {
                    $query->scope('status')->visible(['id']);
                };
            }
        }

        $model = $this->addI18n($this)
            ->where('o.id', $id)
            ->with($relationArray)
            ->visible($this->getAllowRead())
            ->find();

        if ($model) {
            $model = $model->toArray();
            
            if ($relationModel) {
                foreach ($relationModel as $relation) {
                    $model[$relation] = extractValues($model[$relation], 'id');
                }
            }

            $layout = $this->editBuilder($id, $this->getAddonData(['id' => $id]));
            $layout['dataSource'] = $model;

            return $this->success('', $layout);
        } else {
            return $this->error(__('no target'));
        }
    }
}
