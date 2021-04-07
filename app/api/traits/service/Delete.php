<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Delete
{
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

            if ($type === 'deletePermanently') {
                $dataSet = $this->withTrashed()->whereIn('id', array_unique($allIds))->select();
                if (!$dataSet->isEmpty()) {
                    $body = $dataSet->toArray();
                    foreach ($dataSet as $item) {
                        $item->force()->delete();
                    }
                    $result = true;
                } else {
                    return $this->error('Nothing to do.');
                }
            } else {
                $result = $this->withTrashed()->whereIn('id', array_unique($allIds))->select()->delete();
            }
            
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
