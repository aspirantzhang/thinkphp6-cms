<?php

declare(strict_types=1);

namespace app\api\traits\service;

trait Misc
{
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
