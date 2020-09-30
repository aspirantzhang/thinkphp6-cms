<?php

declare(strict_types=1);

namespace app\backend\traits;

use think\facade\Db;

trait Service
{
    public function listAPI($params = [], $withRelation = [])
    {
        return $this->getListData($params, $withRelation);
    }

    public function paginatedListAPI($params, $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';

        $layout = $this->buildList($this->getAddonData($params), $params);
        $layout['page']['trash'] = $params['trash'] == 'onlyTrashed' ? true : false;
        $layout['dataSource'] = [];
        $layout['meta'] = [
            'total' => 0,
            'per_page' => 10,
            'page' => 1,
        ];

        $data = $this->getPaginatedListData($params, $withRelation);
        if ($data) {
            $layout['dataSource'] = $data['dataSource'];
            $layout['meta'] = $data['pagination'];
        }

        return resSuccess('', $layout);
    }

    public function treeListAPI($params, $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        
        $layout = $this->buildList($this->getAddonData(), $params);
        $layout['page']['trash'] = $params['trash'] == 'onlyTrashed' ? true : false;
        $layout['dataSource'] = [];
        $layout['meta'] = [
            'total' => 0,
            'per_page' => 10,
            'page' => 1,
        ];

        $data = $this->getListData($params, $withRelation);
        if ($data) {
            $layout['dataSource'] = arrayToTree($data);
        }
        
        return resSuccess('', $layout);
    }

    /**
     * Get the tree structure of the list data of a particular model.
     * @param mixed $params e.g.: ['status' => 1]
     * @return array
     */
    public function treeDataAPI($params = [], $withRelation = [])
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $data = $this->getListData($params, $withRelation);
        if ($data) {
            if (!isset($data[0]['parent_id'])) {
                return [];
            }
            $data = array_map(function ($model) {

                return array(
                    'id' => $model['id'],
                    'key' => $model['id'],
                    'value' => $model['id'],
                    'title' => $model['name'],
                    'parent_id' => $model['parent_id'],
                );
            }, $data);

            return arrayToTree($data);
        }
        return [];
    }

    public function addAPI()
    {
        $page = $this->buildAdd($this->getAddonData());
        
        if ($page) {
            return resSuccess('', $page);
        } else {
            return resError('Get page data failed.');
        }
    }

    public function saveAPI($data, array $relationModel = [])
    {
        if ($this->checkUniqueFields($data) === false) {
            return resError($this->error);
        }
        $this->startTrans();
        try {
            $this->allowField($this->allowSave)->save($data);
            if ($relationModel) {
                foreach ($relationModel as $relation) {
                    $data[$relation] = $data[$relation] ?? [];
                    $this->$relation()->sync($data[$relation]);
                }
            }
            $this->commit();
            return resSuccess('Add successfully.');
        } catch (\Exception $e) {
            $this->rollback();
            return resError('Save failed.');
        }
    }
    
    public function readAPI($id, $relationModel = [])
    {
        $relationArray = [];

        if ($relationModel) {
            foreach ($relationModel as $relation) {
                $relationArray[$relation] = function ($query) {
                    $query->scope('status')->visible(['id']);
                };
            }
        }

        $model = $this->where('id', $id)->with($relationArray)->visible($this->allowRead)->find();

        if ($model) {
            $model = $model->toArray();
            
            if ($relationModel) {
                foreach ($relationModel as $relation) {
                    $model[$relation] = extractValues($model[$relation], 'id');
                }
            }

            $layout = $this->buildEdit($id, $this->getAddonData(['id' => $id]));
            $layout['dataSource'] = $model;

            return resSuccess('', $layout);
        } else {
            return resError('Target not found.');
        }
    }

    public function updateAPI($id, $data, array $relationModel = [])
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($this->checkUniqueFields($data) === false) {
                return resError($this->error);
            }
            $model->startTrans();
            try {
                $model->allowField($this->allowUpdate)->save($data);
                if ($relationModel) {
                    foreach ($relationModel as $relation) {
                        $model->$relation()->sync($data[$relation]);
                    }
                }
                $model->commit();

                return resSuccess('Update successfully.');
            } catch (\Exception $e) {
                $model->rollback();

                return resError('Update failed.');
            }
        } else {
            return resError('Target not found.');
        }
    }

    public function deleteAPI($ids = [], $type = 'delete')
    {
        if ($ids) {
            $allIds = [];
            // handle descendant
            $tree = $this->treeDataAPI(['trash' => 'withTrashed']);
            $allIds = [];
            foreach ($ids as $id) {
                $allIds[] = (int)$id;
                $allIds = array_merge($allIds, getDescendantSet('id', 'id', $id, $tree));
            }

            if ($type === 'deletePermanently') {
                $dataSet = $this->withTrashed()->whereIn('id', array_unique($allIds))->select();
                foreach ($dataSet as $item) {
                    $item->force()->delete();
                }
                $result = true;
            } else {
                $result = $this->withTrashed()->whereIn('id', array_unique($allIds))->select()->delete();
            }

            if ($result) {
                return resSuccess('Delete successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Nothing to do.');
        }
    }

    public function restoreAPI($ids = [])
    {
        if ($ids) {
            $dataSet = $this->withTrashed()->whereIn('id', array_unique($ids))->select();
            if (!$dataSet->isEmpty()) {
                $tree = $this->treeDataAPI(['trash' => 'withTrashed']);

                foreach ($dataSet as $item) {
                    $item->restore();
                    // is children
                    if (isset($item['parent_id']) && $item['parent_id'] != 0 && !in_array($item['parent_id'], $ids)) {
                        $trashedParent = $this->onlyTrashed()->find($item['parent_id']);
                        if ($trashedParent) {
                            $item->parent_id = 0;
                            $item->save();
                        }
                    }

                    // is parent
                    $childIds = getDescendantSet('id', 'id', $item['id'], $tree, false);
                    if ($childIds) {
                        $exceptChildIds = array_diff($childIds, $ids);
                        if ($exceptChildIds) {
                            foreach ($exceptChildIds as $id) {
                                $this->clearParentId($id);
                            }
                        }
                    }
                }
                return resSuccess('Restore successfully.');
            }
            return resError('Nothing to do.');
        } else {
            return resError('Nothing to do.');
        }
    }

    public function getAllRelationByField(string $fieldName, $fieldValue, string $relationModelName)
    {
        if (is_array($fieldValue)) {
            $result = [];
            $relation = $this->whereIn($fieldName, $fieldValue)->with($relationModelName)->select();
            if (!$relation->isEmpty()) {
                foreach (array_column($relation->toArray(), 'groups') as $relationItem) {
                    $result = array_merge($result, $relationItem);
                }
                return $result;
            }
        } else {
            $relation = $this->where($fieldName, $fieldValue)->with($relationModelName)->find();
            if ($relation) {
                return $relation->toArray()[$relationModelName];
            }
        }

        return [];
    }

    public function getRelationFieldByIdAPI(int $id, string $relationModelName, $fieldName = 'id')
    {
        $relation = $this->where('id', $id)->with($relationModelName)->select();
        if (!$relation->isEmpty()) {
            return extractValues($relation->toArray(), $fieldName, $relationModelName);
        }
        return [];
    }
    
    public function getIDsByRelationIDsAPI(array $relationModelIDs = [], string $selfModelName = ''): array
    {
        $relationModel = $this->whereIn('id', $relationModelIDs)->with([$selfModelName])->select();
        if (!$relationModel->isEmpty()) {
            $IDs = extractValues($relationModel->toArray(), 'id', $selfModelName);
            return $IDs;
        }
        return [];
    }
}
