<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Restore
{
    private function hasParent(array $record)
    {
        return isset($record['parent_id']) && $record['parent_id'] !== 0;
    }

    /**
     * if the record has parent, but it's parent is not included in this operation.
     * meanwhile, his parent is still in the trash.
     * that means, we have to change the record's parent_id to zero
     * to let it has the correct level position in the tree structure.
     * @return bool
     */
    private function shouldResetParent(array $record, array $ids)
    {
        if ($this->hasParent($record) && !in_array($record['parent_id'], $ids)) {
            $parentInTheTrash = $this->onlyTrashed()->find($record['parent_id']);
            return (bool)$parentInTheTrash;
        }
        return false;
    }

    private function handleChildrenRestore(array $record, array $ids)
    {
        $tree = $this->treeDataAPI(['trash' => 'withTrashed']);
        // get all the ids of the record's children
        $childrenIds = searchDescendantValueAggregation('id', 'id', (int)$record['id'], $tree, false);
        if (!empty($childrenIds)) {
            // calculate the id that not included in this operation
            // they should set the parent_id = 0
            $shouldClearIds = array_diff($childrenIds, $ids);
            if (!empty($shouldClearIds)) {
                foreach ($shouldClearIds as $id) {
                    $this->clearParentId($id);
                }
            }
        }
    }

    public function restoreAPI(array $ids = [])
    {
        if (!empty($ids)) {
            $records = $this->withTrashed()->whereIn('id', array_unique($ids))->select();
            if (!$records->isEmpty()) {
                foreach ($records as $record) {
                    $record->restore();
                    if ($this->shouldResetParent($record->toArray(), $ids)) {
                        $record->parent_id = 0;
                        $record->save();
                    }
                    $this->handleChildrenRestore($record->toArray(), $ids);
                }
                return $this->success(__('restore successfully'));
            }
        }
        return $this->error(__('no target'));
    }
}
