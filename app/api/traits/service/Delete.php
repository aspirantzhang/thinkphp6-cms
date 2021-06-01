<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Db;

trait Delete
{
    protected function deleteI18nData($originalId)
    {
        Db::name($this->getLangTableName())->where('original_id', $originalId)->delete();
    }

    public function deleteAPI($ids = [], $type = 'delete')
    {
        if (!empty($ids)) {
            // handle descendant
            $tree = $this->treeDataAPI(['trash' => 'withTrashed']);
            $allIds = [];
            $body = [];
            foreach ($ids as $id) {
                $allIds[] = (int)$id;
                $allIds = array_merge($allIds, getDescendantSet('id', 'id', $id, $tree));
            }

            $dataSet = $this->withTrashed()->whereIn('id', array_unique($allIds))->select();
            if (!$dataSet->isEmpty()) {
                $body = $dataSet->toArray();
                foreach ($dataSet as $item) {
                    if ($type === 'deletePermanently') {
                        $item->force()->delete();
                        $this->deleteI18nData($item->id);
                    } else {
                        $item->delete();
                    }
                }
                $result = true;
            } else {
                return $this->error('Nothing to do.');
            }

            // if ($type === 'deletePermanently') {
            //     $dataSet = $this->withTrashed()->whereIn('id', array_unique($allIds))->select();
            //     if (!$dataSet->isEmpty()) {
            //         $body = $dataSet->toArray();
            //         foreach ($dataSet as $item) {
            //             $this->deleteI18nData($item->id);
            //             $item->force()->delete();
            //         }
            //         $result = true;
            //     } else {
            //         return $this->error('Nothing to do.');
            //     }
            // } else {
            //     $result = $this->withTrashed()->whereIn('id', array_unique($allIds))->select()->delete();
            // }
            
            if ($result) {
                return $this->success('Delete successfully.', $body);
            } else {
                return $this->error('Delete failed.');
            }
        } else {
            return $this->error('Nothing to do.');
        }
    }
}
