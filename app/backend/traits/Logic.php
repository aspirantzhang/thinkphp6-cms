<?php

declare(strict_types=1);

namespace app\backend\traits;

trait Logic
{
    
    /**
     * Get the list data.
     * @param mixed $requestParams Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getListData($requestParams = [], array $withRelation = []): array
    {
        $search = getSearchParam($requestParams, $this->allowSearch);
        $sort = getSortParam($requestParams, $this->allowSort);
        
        return $this->with($withRelation)
                    ->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->select()
                    ->toArray();
    }

    /**
     * Get the list data with pagination.
     * @param mixed $requestParams Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getPaginatedListData($requestParams = [], array $withRelation = []): array
    {
        $search = getSearchParam($requestParams, $this->allowSearch);
        $sort = getSortParam($requestParams, $this->allowSort);
        $perPage = getPerPageParam($requestParams);
        
        return $this->with($withRelation)
                    ->withSearch(array_keys($search), $search)
                    ->order($sort['name'], $sort['order'])
                    ->visible($this->allowList)
                    ->paginate($perPage)
                    ->toArray();
    }

    protected function getParentData($exceptID = 0)
    {
        $data = $this->getListData();
        $data = array_map(function ($model) use ($exceptID) {
            if ($model['id'] != $exceptID) {
                return [
                    'id' => $model['id'],
                    'key' => $model['id'],
                    'value' => $model['id'],
                    'title' => $model['name'],
                    'parent_id' => $model['parent_id'],
                ];
            } else {
                return null;
            }
        }, $data);

        $data[] = [
            'id' => 0,
            'key' => 0,
            'value' => 0,
            'title' => 'Top',
            'parent_id' => -1,
        ];

        // filter null
        return array_filter($data);
    }

    /**
     * Check the values of the unique fields.
     * @param mixed $data Request data
     * @param mixed $uniqueFields Associative array of unique fields. Default: $this->unique
     * @return bool
     */
    protected function checkUniqueFields($data, $uniqueFields = []): bool
    {
        $uniqueFields = $this->unique ?? [];
        if (is_array($uniqueFields) && count($uniqueFields)) {
            foreach ($this->unique as $field => $title) {
                if ($this->ifExists($field, $data[$field])) {
                    $this->error = 'The ' . $title . ' already exists.';
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check whether a value already exists.
     * @param string $fieldName
     * @param mixed $value
     * @return bool
     */
    protected function ifExists(string $fieldName, $value)
    {
        $result = $this->withTrashed()->where($fieldName, $value)->find();
        return (bool)$result;
    }
}
