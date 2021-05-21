<?php

declare(strict_types=1);

namespace app\api\traits;

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
        $search = getSearchParam($params, $this->getAllowSearch());
        $sort = getSortParam($params, $this->getAllowSort());

        $result = $this;

        if ($params['trash'] !== 'withoutTrashed') {
            $result = $result->{$params['trash'] == 'onlyTrashed' ? 'onlyTrashed' : 'withTrashed'}();
        }

        return $this->addI18n($result->with($withRelation))
            ->withSearch(array_keys($search), $search)
            ->order($sort['name'], $sort['order'])
            ->visible($this->getAllowList())
            ->select()
            ->toArray();
    }

    /**
     * Get the list data with pagination.
     * @param mixed $params Request parameters used for search, sort, pagination etc.
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getPaginatedListData($params = [], array $withRelation = []): array
    {
        $search = getSearchParam($params, $this->getAllowSearch());
        $sort = getSortParam($params, $this->getAllowSort());
        $perPage = $params['per_page'] ?? 10;
       
        $result = $this;

        if ($params['trash'] !== 'withoutTrashed') {
            $result = $result->{$params['trash'] == 'onlyTrashed' ? 'onlyTrashed' : 'withTrashed'}();
        }

        return $this->addI18n($result->with($withRelation))
            ->withSearch(array_keys($search), $search)
            ->order($sort['name'], $sort['order'])
            ->visible($this->getAllowList())
            ->paginate($perPage)
            ->toArray();
    }

    /**
     * Check if the value is unique
     * @param mixed $data Request data
     * @return bool
     */
    protected function checkUniqueFields($data): bool
    {
        $uniqueFields = $this->unique ?? [];
        if (is_array($uniqueFields) && !empty($uniqueFields)) {
            foreach ($uniqueFields as $field => $title) {
                if (isset($data[$field]) && $this->ifExists($field, $data[$field])) {
                    $this->error = 'The ' . $title . ' already exists.';
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Check if a value already exists in the database
     * @param string $fieldName
     * @param mixed $value
     * @return bool
     */
    protected function ifExists(string $fieldName, $value)
    {
        if ($this->isTranslateField($fieldName)) {
            return (bool)Db::name($this->getLangTableName())->where($fieldName, $value)->where('original_id', '<>', $this->id)->find();
        }
        return (bool)$this->withTrashed()->where($fieldName, $value)->where('id', '<>', $this->id)->find();
    }

    protected function clearParentId($id)
    {
        Db::table($this->getTableName())
            ->where('id', $id)
            ->update(['parent_id' => 0]);
        return true;
    }
}
