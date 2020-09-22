<?php

declare(strict_types=1);

namespace app\backend\traits;

trait Service
{
    public function listAPI($requestParams = [], $withRelation = [])
    {
        return $this->getListData($requestParams, $withRelation);
    }

    public function paginatedListAPI($requestParams, $withRelation = [])
    {
        $data = $this->getPaginatedListData($requestParams, $withRelation);

        if ($data) {
            $layout = $this->buildList($this->getAddonData());

            $layout['dataSource'] = $data['dataSource'];
            $layout['meta'] = $data['pagination'];

            return resSuccess('', $layout);
        } else {
            return resError('Get list data failed.');
        }
    }

    public function treeListAPI($requestParams, $withRelation = [])
    {
        $data = $this->treeDataAPI($requestParams, $withRelation);

        if ($data) {
            $layout = $this->buildList($this->getAddonData());

            $layout['dataSource'] = $data;
            $layout['meta'] = [
                'total' => 0,
                'per_page' => 10,
                'page' => 1,
            ];

            return resSuccess('', $layout);
        } else {
            return resError('Get list data failed.');
        }
    }

    /**
     * Get the tree structure of the list data of a particular model.
     * @param mixed $requestParams e.g.: ['status' => 1]
     * @return array
     */
    protected function treeDataAPI($requestParams = [], $withRelation = [])
    {
        $data = $this->getListData($requestParams, $withRelation);
        if ($data) {
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
            return false;
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

            $layout = $this->buildEdit($id, $this->getAddonData());
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

    public function deleteAPI($id)
    {
        $model = $this->scope('status')->find($id);
        if ($model) {
            if ($model->delete()) {
                return resSuccess('Delete successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Target not found.');
        }
    }

    public function batchDeleteAPI($idArray)
    {
        if (count($idArray)) {
            $result = $this->whereIn('id', $idArray)->select()->delete();
            if ($result) {
                return resSuccess('Delete successfully.');
            } else {
                return resError('Delete failed.');
            }
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
