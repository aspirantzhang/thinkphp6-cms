<?php

declare(strict_types=1);

namespace app\api\traits;

use app\api\service\Model as ModelService;

trait Model
{
    protected function getTableName()
    {
        return parse_name($this->getName());
    }

    // Accessor
    public function getCreateTimeAttr($value)
    {
        $date = new \DateTime($value);
        return $date->format('Y-m-d\TH:i:sP');
    }
    public function getUpdateTimeAttr($value)
    {
        $date = new \DateTime($value);
        return $date->format('Y-m-d\TH:i:sP');
    }

    // Mutator

    // Searcher
    public function searchIdAttr($query, $value, $data)
    {
        $query->where('id', $value);
    }

    public function searchStatusAttr($query, $value, $data)
    {
        $value = (string)$value;
        if (strlen($value)) {
            if (strpos($value, ',')) {
                $query->whereIn('status', $value);
            } else {
                $query->where('status', $value);
            }
        }
    }

    public function searchCreateTimeAttr($query, $value, $data)
    {
        $value = urldecode($value);
        $valueArray = (array)explode(',', $value);
        $query->whereBetweenTime('create_time', $valueArray[0], $valueArray[1]);
    }

    // Other
    protected function getModelData($fieldName = '')
    {
        if ($fieldName) {
            $fieldData = ModelService::where('table_name', $this->getTableName())->find();
            return $fieldData['data'] ?? [];
        } else {
            return ModelService::where('table_name', $this->getTableName())->find();
        }
    }

    protected function getModelFields($type = null)
    {
        $data = $this->getModelData('data');
        if ($data) {
            switch ($type) {
                case 'home':
                case 'list':
                    $rawArray = array_filter((array)$data['fields'], function ($value) {
                        if (isset($value['listHideInColumn']) && $value['listHideInColumn'] === '1') {
                            return false;
                        }
                        return true;
                    });
                    return extractValues($rawArray, 'name');
                case 'sort':
                    $rawArray = array_filter($data['fields'], function ($value) {
                        if (isset($value['listSorter']) && $value['listSorter'] === '1') {
                            return true;
                        }
                        return false;
                    });
                    return extractValues($rawArray, 'name');
                case 'update':
                    $rawArray = array_filter($data['fields'], function ($value) {
                        if (isset($value['editDisabled']) && $value['editDisabled'] === '1') {
                            return false;
                        }
                        return true;
                    });
                    return extractValues($rawArray, 'name');
                case 'read':
                case 'save':
                case 'search':
                case 'all':
                    return extractValues($data['fields'], 'name');
                default:
                    return [];
            }
        }
        return [];
    }
}
