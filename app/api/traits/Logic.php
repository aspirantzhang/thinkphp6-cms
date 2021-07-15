<?php

declare(strict_types=1);

namespace app\api\traits;

use think\facade\Db;
use think\facade\Lang;
use think\facade\Config;
use think\helper\Str;
use think\Exception;

trait Logic
{
    public function addTranslationStatus($rawDataSource)
    {
        $dataSource = [];

        // add lang element for all
        $languages = Config::get('lang.allow_lang_list');
        foreach ($rawDataSource as $record) {
            foreach ($languages as $langCode) {
                $record['i18n'][$langCode] = null;
            }
            $dataSource[] = $record;
        }

        $ids = array_column($dataSource, 'id');
        $idsFlipped = array_flip($ids);
        $i18nData = Db::table($this->getLangTableName())->whereIn('original_id', implode(',', $ids))->select()->toArray();
        foreach ($i18nData as $i18n) {
            $originalIdIndex = $idsFlipped[$i18n['original_id']];
            // $record['i18n']['en-us'] = '2021-06-18T14:33:38+08:00';
            $translateTime = $i18n['translate_time'] ? (new \DateTime($i18n['translate_time']))->format('Y-m-d\TH:i:sP') : null;
            $dataSource[$originalIdIndex]['i18n'][$i18n['lang_code']] = $translateTime;
        }

        return $dataSource;
    }
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

    
    protected function checkUniqueValues($data): bool
    {
        $uniqueFields = $this->getUniqueField();
        foreach ($uniqueFields as $field) {
            if (isset($data[$field]) && $this->ifExists($field, $data[$field])) {
                $this->error = __('field value already exists', ['fieldName' => Lang::get($this->getTableName() . '.' . $field)]);
                return false;
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

    protected function handleMutator($fieldsData)
    {
        foreach ($fieldsData as $fieldName => $fieldValue) {
            $mutator = 'set' . Str::studly($fieldName) . 'Attr';
            if (method_exists($this, $mutator)) {
                $fieldsData[$fieldName] = $this->$mutator($fieldValue);
            }
        }
        return $fieldsData;
    }

    protected function saveI18nData(array $rawData, int $originalId, string $langCode, $translateTime = null)
    {
        // keep only allowed
        $filteredData = array_intersect_key($rawData, array_flip($this->getAllowTranslate()));
        // sync translate time if specific
        if ($translateTime) {
            $filteredData['translate_time'] =  $translateTime;
        }
        $data = array_merge($filteredData, [
            'original_id' => $originalId,
            'lang_code' => $langCode
        ]);
        Db::startTrans();
        try {
            Db::name($this->getLangTableName())->save($data);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw new Exception(__('failed to store i18n data'));
        }
    }

    protected function updateI18nData($rawData, $originalId, $langCode, $currentTime = null)
    {
        $filteredData = array_intersect_key($rawData, array_flip($this->getAllowTranslate()));

        if (isset($rawData['complete']) && (bool)$rawData['complete'] === true) {
            $filteredData['translate_time'] =  $currentTime;
        }

        $record = Db::name($this->getLangTableName())
            ->where('original_id', $originalId)
            ->where('lang_code', $langCode)
            ->find();

        if ($record) {
            // update
            try {
                Db::name($this->getLangTableName())
                    ->where('original_id', $originalId)
                    ->where('lang_code', $langCode)
                    ->update($filteredData);
                return true;
            } catch (\Throwable $e) {
                $this->error = __('failed to store i18n data');
                return false;
            }
        }
        // add new
        if (isset($rawData['complete']) && (bool)$rawData['complete'] === true) {
            return $this->saveI18nData($rawData, $originalId, $langCode, $currentTime);
        }
        return $this->saveI18nData($rawData, $originalId, $langCode);
    }
}
