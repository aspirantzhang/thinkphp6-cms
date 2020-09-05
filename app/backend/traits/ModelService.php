<?php

declare(strict_types=1);

namespace app\backend\traits;

trait ModelService
{
    public function listAPI($requestParams = [], $withRelation = [])
    {
        return $this->getListData($requestParams, $withRelation);
    }

    public function paginatedListAPI($requestParams, $withRelation = [])
    {
        $data = $this->getPaginatedListData($requestParams, $withRelation);

        if ($data) {
            $layout = $this->buildList($this->getAddonData())->toArray();

            $layout['dataSource'] = $data['dataSource'];
            $layout['meta'] = $data['pagination'];

            return resSuccess('', $layout);
        } else {
            return resError('Get list failed.');
        }
    }

    public function treeListAPI($requestParams, $withRelation = [])
    {
        $data = $this->getListData($requestParams, $withRelation);

        if ($data) {
            $layout = $this->buildList($this->getAddonData())->toArray();

            $layout['dataSource'] = arrayToTree($data);
            $layout['meta'] = [
                'total' => 0,
                'per_page' => 10,
                'page' => 1,
            ];

            return resSuccess('', $layout);
        } else {
            return resError('Get list failed.');
        }
    }

    public function addAPI()
    {
        $page = $this->buildAdd($this->getAddonData())->toArray();
        
        if ($page) {
            return resSuccess('', $page);
        } else {
            return resError('Get page failed.');
        }
    }

    public function saveAPI($data)
    {
        $result = $this->saveNew($data);
        if ($result) {
            return resSuccess('Add successfully.');
        } else {
            return resError($this->error);
        }
    }
    
    public function readAPI($id)
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            $model = $model->visible($this->allowRead)->toArray();
           
            $layout = $this->buildEdit($id, $this->getAddonData())->toArray();
            $layout['dataSource'] = $model;

            return resSuccess('', $layout);
        } else {
            return resError('Target not found.');
        }
    }

    public function updateAPI($id, $data)
    {
        $model = $this->where('id', $id)->find();
        if ($model) {
            if ($model->allowField($this->allowUpdate)->save($data)) {
                return resSuccess('Update successfully.');
            } else {
                return resError('Update failed.');
            }
        } else {
            return resError('Target not found.');
        }
    }

    public function deleteAPI($id)
    {
        $model = $this->find($id);
        if ($model) {
            if ($model->delete()) {
                return resSuccess('Delete completed successfully.');
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
                return resSuccess('Delete completed successfully.');
            } else {
                return resError('Delete failed.');
            }
        } else {
            return resError('Nothing to do.');
        }
    }
    
    public function getIDsByRelationIDsAPI(array $relationModelIDs = [], string $selfModelName = ''): array
    {
        $relationModel = $this->whereIn('id', $relationModelIDs)->with([$selfModelName])->select();
        if (!$relationModel->isEmpty()) {
            $IDs = extractUniqueValuesInArray($relationModel->toArray(), $selfModelName, 'id');
            return $IDs;
        }
        return [];
    }
}
