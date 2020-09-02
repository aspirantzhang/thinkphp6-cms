<?php

declare(strict_types=1);

namespace app\backend\traits;

trait ModelLogic
{
    /**
     * Get the data of a paginated list.
     * @param mixed $requestParams Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getPaginatedListData($requestParams, array $withRelation = []): array
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
