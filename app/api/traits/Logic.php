<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Db;
use think\facade\Lang;

trait Logic
{
    
    /**
     * Get the list data.
     * @param mixed $parameters Request parameters
     * @param array $withRelation Relational model array used for Model->with() argument.
     * @return array
     */
    protected function getListData(array $parameters = [], array $withRelation = [], string $type = 'normal'): array
    {
        $params = getListParams($parameters, $this->getAllowHome(), $this->getAllowSort());
        $result = $this;

        if ($params['trash'] !== 'withoutTrashed') {
            $result = $result->{$params['trash'] == 'onlyTrashed' ? 'onlyTrashed' : 'withTrashed'}();
        }

        $result = $this->addI18n($result->with($withRelation))
            ->withSearch($params['search']['keys'], $params['search']['values'])
            ->order($params['sort']['name'], $params['sort']['order'])
            ->visible($params['visible']);

        if ($type === 'paginated') {
            return $result->paginate($params['per_page'])->toArray();
        }
        return $result->select()->toArray();
    }

    /**
     * Check if the value is unique
     * @param mixed $data Request data
     * @return bool
     */
    protected function checkUniqueFields($data, $modelName): bool
    {
        $uniqueFields = $this->uniqueField ?? [];
        if (is_array($uniqueFields) && !empty($uniqueFields)) {
            foreach ($uniqueFields as $field) {
                if (isset($data[$field]) && $this->ifExists($field, $data[$field])) {
                    halt(Lang::get($modelName . '.' . $field), Lang::get());
                    $this->error = __('field value already exists', ['fieldName' => Lang::get($modelName . '.' . $field)]);
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
