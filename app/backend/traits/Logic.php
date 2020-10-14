<?php

declare(strict_types=1);

namespace app\backend\traits;

use think\facade\Db;

trait Logic
{
    
    /**
     * Get the list data.
     * @param mixed $params Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getListData($params = [], array $withRelation = []): array
    {
        $params['trash'] = $params['trash'] ?? 'withoutTrashed';
        $search = getSearchParam($params, $this->allowSearch);
        $sort = getSortParam($params, $this->allowSort);

        if ($params['trash'] !== 'withoutTrashed') {
            $trashConfig = ($params['trash'] == 'onlyTrashed') ? 'onlyTrashed' : 'withTrashed';

            return $this->$trashConfig()
                        ->with($withRelation)
                        ->withSearch(array_keys($search), $search)
                        ->order($sort['name'], $sort['order'])
                        ->visible($this->allowList)
                        ->select()
                        ->toArray();
        } else {
            return $this->with($withRelation)
                        ->withSearch(array_keys($search), $search)
                        ->order($sort['name'], $sort['order'])
                        ->visible($this->allowList)
                        ->select()
                        ->toArray();
        }
    }

    /**
     * Get the list data with pagination.
     * @param mixed $params Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getPaginatedListData($params = [], array $withRelation = []): array
    {
        $search = getSearchParam($params, $this->allowSearch);
        $sort = getSortParam($params, $this->allowSort);
        $perPage = getPerPageParam($params);
       
        if ($params['trash'] !== 'withoutTrashed') {
            $trashConfig = ($params['trash'] == 'onlyTrashed') ? 'onlyTrashed' : 'withTrashed';
            
            return $this->$trashConfig()
                        ->with($withRelation)
                        ->withSearch(array_keys($search), $search)
                        ->order($sort['name'], $sort['order'])
                        ->visible($this->allowList)
                        ->paginate($perPage)
                        ->toArray();
        }
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
        if (!isParentArray($data)) {
            $this->error = 'No parent_id';
            return [];
        }
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
        if (is_array($uniqueFields) && $uniqueFields) {
            foreach ($this->unique as $field => $title) {
                if (isset($data[$field]) && $this->ifExists($field, $data[$field])) {
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

    protected function clearParentId($id)
    {
        Db::table(parse_name($this->name))
            ->where('id', $id)
            ->update(['parent_id' => 0]);
        return true;
    }
}
