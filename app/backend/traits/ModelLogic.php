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

    /**
     * Save a new record. No transaction!!!
     * @param mixed $data
     * @return mixed False or new record's id.
     */
    protected function saveNew($data)
    {
        if ($this->checkUniqueFields($data) === false) {
            return false;
        }

        $result = $this->save($data);
        if ($result) {
            return $this->getData('id');
        } else {
            $this->error = 'Save failed.';
            return false;
        }
    }
}
