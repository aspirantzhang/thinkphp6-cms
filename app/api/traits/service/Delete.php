<?php

declare(strict_types=1);

namespace app\api\traits\service;

use think\facade\Db;
use think\Exception;

trait Delete
{
    protected function deleteI18nData(int $originalId)
    {
        try {
            Db::name($this->getLangTableName())->where('original_id', $originalId)->delete();
        } catch (Exception $e) {
            throw new Exception(__('remove i18n record failed'));
        }
    }

    public function deleteAPI(array $ids = [], string $type = 'delete')
    {
        if (!empty($ids)) {
            // handle descendant
            $tree = $this->treeDataAPI(['trash' => 'withTrashed']);
            $allIds = [];
            $body = [];
            foreach ($ids as $id) {
                $id = (int)$id;
                $allIds[] = $id;
                $allIds = array_merge($allIds, searchDescendantValueAggregation('id', 'id', $id, $tree));
            }

            $dataSet = $this->withTrashed()->whereIn('id', array_unique($allIds))->select();
            if (!$dataSet->isEmpty()) {
                $body = $dataSet->toArray();
                foreach ($dataSet as $item) {
                    if ($type === 'deletePermanently') {
                        $item->force()->delete();
                        $this->deleteI18nData((int)$item->id);
                    } else {
                        $item->delete();
                    }
                }
                return $this->success(__('delete successfully'), $body);
            } else {
                return $this->error(__('no target'));
            }
        } else {
            return $this->error(__('no target'));
        }
    }
}
